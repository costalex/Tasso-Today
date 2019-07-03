<?php

namespace App\Http\Controllers;

use function abort;
use App\Paiement;
use App\Mail\CustomMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Exception;
use Illuminate\Support\Facades\Mail;
use function json_decode;


class PaiementController extends Controller
{
	/**
	 * CREATE
	 */

	/**
	 * Creation d'un compte stripe customers pour les paiements liés au compte stripe de Tasso
	 * @param Request $request
	 */
	public function createCustomerAccount(Request $request)
	{
		if (!Auth::user()->hasStripeId())
			Auth::user()->createAsStripeCustomer($request->stripeToken);
		else
			Auth::user()->updateCard($request->stripeToken);

		abort(200, "Compte de paiement créer.");
	}

	/**
	 * UPDATE
	 */

	/**
	 * Modification de la carte par defaut de l'utilisateur
	 * @param Request $request
	 */
	public function changeDefaultCard(Request $request)
	{
		$customer = Auth::user()->asStripeCustomer();
		$customer->default_source = $request->default_source;
		$customer->save();
		Auth::user()->updateCardFromStripe();
		abort(200, 'Carte par defaut modifiée');
	}

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation des cartes enregistrées par l'utilisateur
	 * @return mixed
	 */
	public function getPaiementCardList()
	{
		return Auth::user()->asStripeCustomer()->sources->all(
			['object' => 'card'])->data;
	}

	/**
	 * PAIEMENT - REMBOURSEMENT
	 */

	/**
	 * Paiement en se servant de la carte par defaut
	 * @param Request $request
	 */
	public function paiement(Request $request)
	{
		if (app('App\Http\Controllers\FactureController')->checkFactStatutBeforePaiement($request->num_fact) &&
			app('App\Http\Controllers\FactureController')->checkStockFactForPaiement($request->num_fact))
		{
			//verification du paiement.
			$paiement_infos = null;
			try{
				$paiement_infos = Auth::user()->charge(app('App\Http\Controllers\FactureController')
						->getTotalFacture($request->num_fact)*100);
			}catch (\Exception $e){
				abort(400, 'Paiement échoué (' . $e->getMessage() . ')');
			}

			if ($paiement_infos)
			{
				app('App\Http\Controllers\FactureController')->updateStockAndStatutAfterPaiement($request->num_fact);
				$paiement = Paiement::firstOrCreate(
					[
						'charge_id' => $paiement_infos->id,
						'paiement_infos' => $paiement_infos,
					]);
				$facture = app('App\Http\Controllers\FactureController')->getFactByNum($request->num_fact)['facture'];
				$facture->paiement_id = $paiement['id'];
				$facture->save();

				//Envoie de la facture par mail.
				$mail_fact = new CustomMail();
				$mail_fact->setAddressTo(Auth::user()->email);
				$mail_fact->setAddressFrom([config('mail.from.address'), config('mail.from.name')]);
				$mail_fact->setSubject('Facture Tasso.');
				$mail_fact->setViewTemplate('facturation.facture_client');
				$mail_fact->setTemplateDatas([
					'title' => 'Facture',
					'facture' => $facture['facture'],
					'impression' => false,
				]);
				Mail::send($mail_fact);

				abort(200, 'Paiement effectué');
			}
		}
		else
			abort(400, 'Paiement échoué');
	}

	/**
	 * Procedure de remboursement automatisé sur la modification du statut d'une commande par l'entreprise en 'ANNULE'
	 * @param $num_commande
	 * @param $commentaire_entreprise
	 */
	public function remboursement($num_commande, $commentaire_entreprise)
	{
		//Recuperation des informations de l'entreprise
		$facture_entreprise = app('App\Http\Controllers\FactureController')->getFactByNum($num_commande)['facture'];
		$bon_commande_entreprise = $facture_entreprise->bon_commande;

		//Recuperation des informations de l'acheteur
		$client_id = $facture_entreprise->client_id;
		$client_cmd = app('App\Http\Controllers\FactureController')->getClientCmdFrom($bon_commande_entreprise['num_commande'],$client_id);
		$client = json_decode(app('App\Http\Controllers\ClientInformationsController')->getClientPaimentInformations($client_id)->getContent(), true);

		//Verification que l'utilisateur est bien un client et il faille le rembourser une partie de sa facture, verification qu'il a pu etablir une facture
		if ($client['user_paiement_infos']['user_type']['nom'] != 'Client' && $client['user_paiement_infos']['status'] != 'ACTIVE')
			abort(400, "Les informations de l'utilisateur sont incorrecte.");

		//Verification que le bon de commande entreprise a bien un statut different de 'ANNULE' et que le remboursement n'a pas deja etait fait
		if ($facture_entreprise->statut != 'ANNULE' && !$bon_commande_entreprise['rembourse'])
		{
			$entreprise_found = false;
			foreach ($client_cmd['entreprises'] as $i => $entreprise) {
				//Acceleration du parsing
				if ($i == $bon_commande_entreprise['entreprise_infos']['nom_enseigne']) {
					foreach ($entreprise['paniers'] as $j => $panier) {
						if ($panier['num_commande'] == $bon_commande_entreprise['num_commande']) {
							//Verification du staut de remboursement dans le bon de commande du client (doit coincider avec celui de l'entreprise)
							//Verification que le montant demander dans la facture est bien celui qu'a regler le client
							if ($panier['rembourse'] || $panier['rembourse'] != $bon_commande_entreprise['rembourse'] || $panier['total_facture'] != $bon_commande_entreprise['total_facture'])
								abort(400, "Informations incorrecte entre le client et l'entreprise.");
							else {
								$entreprise_found = true;
								break;
							}
						}
					}
					break;
				}
			}
			//Verification resultant de l'acceleration du parsing
			if (!$entreprise_found)
				abort(400, "L'entreprise n'a pas etait trouvé dans le bon de commande client.");

			//Recuperation des informations de paiement effectué par l'utilisateur
			$client_paiement_id = app('App\Http\Controllers\FactureController')->getClientPaimentIdFor($client_cmd['num_commande'], $client_id);
			$client_paiement_infos = Paiement::select('id', 'paiement_infos')->where(['id' => $client_paiement_id])->first();

			// Verification que le paiement a bien etait fait et que le montant correspont bien a celui que l'on nous demande de rembourser.(infos stripe)
			// Verification que l'objet retourner par strip n'est pas etait modifié
			if (!empty($client_paiement_infos) && !$client_paiement_infos->paiement_infos['paid'] || $client_paiement_infos->paiement_infos['status'] != 'succeeded' ||
				$client_paiement_infos->paiement_infos['outcome']['type'] != 'authorized' || $client_paiement_infos->paiement_infos['outcome']['network_status'] != "approved_by_network" ||
				$client_paiement_infos->paiement_infos['customer'] != $client['user_paiement_infos']['stripe_id'] || $client_paiement_infos->paiement_infos['source']['customer'] != $client['user_paiement_infos']['stripe_id'])
				abort(400, "Les informations de paiement de l'acheteur sont incorrecte.");

			$user = app('App\Http\Controllers\UserInformationsController')->getUserInstance($client['user_paiement_infos']['id']);

			$paiement_infos = null;
			try{
				$paiement_infos = $user->refund($client_paiement_infos->paiement_infos['id'] ,[
					'amount' => $bon_commande_entreprise['total_facture']*100
				]);

				//Re-verification en plus du catch du remboursement
				if ($paiement_infos->status == 'succeeded')
				{
					//creation d'un paiement_id
					$paiement = Paiement::firstOrCreate(
					[
						'charge_id' => $paiement_infos->id,
						'paiement_infos' => $paiement_infos,
					]);

					//Update des information sur la facture et cmd du client et cmd de l'entreprise
					app('App\Http\Controllers\FactureController')->updateFactCmdAnnulation($client_cmd['num_commande'], $bon_commande_entreprise['num_commande'], $paiement['id']);
					app('App\Http\Controllers\FactureController')->updateAnnulationCmd($bon_commande_entreprise['num_commande'], $facture_entreprise->client_id, $commentaire_entreprise);

					//Envoie du justificatif de remboursement par mail.
					$mail_rembourssement = new CustomMail();
					$mail_rembourssement->setAddressTo($client['user_paiement_infos']['email']);
		        	$mail_rembourssement->setAddressFrom([config('mail.from.address'), config('mail.from.name')]);
			        $mail_rembourssement->setViewTemplate('facturation.annulationCommande');
			        $mail_rembourssement->setSubject("Remboursement d'achat Tasso.");
			        $mail_rembourssement->setTemplateDatas([
				        'num_cmd' => $num_commande,
				        'date_creation' => (string)$facture_entreprise->created_at,
						'date_annulation' => (string)$facture_entreprise->updated_at,
						'annulation_commentaire' => $commentaire_entreprise,
			        ]);

                  Mail::send($mail_rembourssement);
				}
			}catch (\Exception $e){
				abort(400, 'Rembourssement échoué (' . $e->getMessage() . ')');
			}
		}
		else
			abort(400, 'Ce bon de commande a deja etait remboursé.');
	}

	/**
	 * DELETE
	 */

	/**
	 * Suppression d'une carte banquaire liée au compte de l'utilisateur
	 * @param Request $request
	 */
	public function deleteCard(Request $request)
	{
		Auth::user()->cards()->each(function ($card) use ($request)
		{
			if ($card->id === $request->card['id'])
			{
				$card->delete();
				abort(200, "Carte banquaire supprimée.");
			}
		});
		abort(400, "Carte banquaire non trouvée.");
	}
}
