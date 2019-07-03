<?php

namespace App\Http\Controllers;

use function abort;
use App\Facture;
use function array_push;
use Barryvdh\DomPDF\PDF as PDF;
use function config;

use function json_encode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMail;
use function json_decode;
use function sizeof;

use function view;
use GuzzleHttp\Client as guzzleClient;
use GuzzleHttp\Exception\ClientException;

class FactureController extends Controller
{
	/**
	 * CREATE
	 */

	/**
	 * Creation de la facture de l'entreprise (stockage des informations en json)
	 * @param Request $request
	 */
	public function createFacture(Request $request)
	{
		//Recuperation des informations du client courant generant la facture
		$client = json_decode(app('App\Http\Controllers\ClientInformationsController')->showClientInformations()->getContent());

		$facture_client = [];
		$facture_client['total'] = 0;
		$facture_client['total_livraison'] = 0;
		$facture_client['livraison'] = $request->livraison;

		foreach ($request->paniers as $panier_entreprise) {
			//Recuperation des informations public de l'entreprise courante
			$entreprise = app('App\Http\Controllers\EntrepriseInformationsController')->getPublicEntrepriseInfosByName($panier_entreprise["shop"]['city'], $panier_entreprise["shop"]['name']);

			//Si le client a choisit la livraison on verifie qu'il y est elligible a l'aide d'ortoo
			if ($request->livraison)
			{
				$check_addresses = $this->checkOrtooAddresses($request->addresse_livraison_client, $entreprise->addresseEntreprise['addresse'] . ' ' .  $entreprise->addresseEntreprise['code_postal'] . ' ' . $entreprise->addresseEntreprise['ville']);

				if (!$check_addresses['result'])
				{
					switch ($check_addresses['message'])
					{
						case 'public_key is not defined': abort(400, "Une erreure est survenue lors de l'accés au service de livraison.");
						case 'Steps is not defined': abort(400, 'Votre addresse est manquante.');
						case 'No address found': abort(400, 'Votre addresse est incorrecte.');
						case 'Estimate price failed': abort(400, "Le service n'a pas pu déterminer le cout de la course.");
						case 'Estimate time failed': abort(400, "Le service n'a pas pu déterminer le temps de la course.");
						case 'OUT_OF_RANGE': abort(400, "Vous n'êtes pas elligible à la livraison, veuillez opter pour le service \"click and collect\"");
						case 'First name, last name, phone number, address and option are mandatory parameters': abort(400, "L'une de vos informations personnelles est manquante.");
						case 'Refcourse generation failed': abort(400, "Une erreure est survenue lors de la creation de course.");
						default: abort(400,  'Une erreure est survenue lors de la verification de votre addresse.');
					}
				}
			}
			$entreprise_produits_list = app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseProduitsListByID($entreprise->id);

			//Verification que les produits existent bien dans l'entreprise ciblée
			$this->checkProduitsList($panier_entreprise['product'], $entreprise_produits_list, $entreprise->nom_enseigne);
			$list_paniers_produits = $this->decoupeProduitsPoidsVolume($panier_entreprise['product'], $entreprise_produits_list);

			//Creation des facture(s) entreprise(s)
			foreach ($list_paniers_produits as $list_produits)
			{
				$num_cmd = str_random(8);

				if (empty(Facture::create([
					'client_id' => $client->id,
					'entreprise_id' => $entreprise->id,
					'user_type_id' => Auth::user()->user_type_id,
					'addresse_livraison_client' => $request->addresse_livraison_client,
					'paiement_id' => 0,
					'num_commande' => $num_cmd,
					'bon_commande' => "{}",
					'facture' => "{}",
					'list_promo' => $panier_entreprise['list_promo'],
					'commentaire_client' => $panier_entreprise['commentaire']
				])))
					abort(400, "Erreure de creation de la facture.");

				$facture = Facture::where([
					'client_id' => $client->id,
					'entreprise_id' => $entreprise->id,
					'num_commande' => $num_cmd
				])
					->with('userType', 'client', 'entreprise')
					->first();

				$facture->client->addresses_livraison = app('App\Http\Controllers\ContactController')->getContactsIn($facture->client->addresses_livraison);

				$facture->facture = $this->generateFacture($facture, "Facture", $list_produits, [
					'livraison' => $request->livraison,
					'information_complémentaire' => $request->information_complémentaire
				]);
				$facture->bon_commande = $this->generateFacture($facture, "Bon de commande", $list_produits,
					[
						'livraison' => $request->livraison,
						'date_livraison' => $request->date_livraison,
						'information_complémentaire' => $request->information_complémentaire
					]);

				$facture->save();

				//Recuperation, cocatenation et formatage des informations de l'entreprise courante pour la creation de la facture et bon de commande du client
				if (!isset($facture_client['entreprises'][$panier_entreprise["shop"]['name']]['paniers']))
					$facture_client['entreprises'][$panier_entreprise["shop"]['name']]['paniers'] = [];
				if (!isset($facture_client['entreprises'][$panier_entreprise["shop"]['name']]['entreprise_infos'])) {
					$facture_client['entreprises'][$panier_entreprise["shop"]['name']]['entreprise_infos'] = $facture->bon_commande['entreprise_infos'];
					$facture_client['entreprises'][$panier_entreprise["shop"]['name']]['entreprise_infos']['date'] = $facture->bon_commande['date'];
				}
				if (!isset($facture_client['client_infos']))
					$facture_client['client_infos'] = [
						'addresse_livraison_client' => $request->addresse_livraison_client,
						'nom' => $facture->bon_commande['client_infos']['nom'],
						'prenom' => $facture->bon_commande['client_infos']['prenom']
					];
				array_push($facture_client['entreprises'][$panier_entreprise["shop"]['name']]['paniers'], $facture->bon_commande);
				$facture_client['total'] = $facture_client['total'] + $facture->bon_commande['total_facture'];
				if ($request->livraison)
					$facture_client['total_livraison'] += 2.90;
			}
		}

		//Creation de la facture client avec la concatenation des bon de commande entreprise precedement recuperer
		$num_cmd = str_random(8);
		$facture_client['num_commande'] = $num_cmd;

		Facture::create([
			'client_id' => $client->id,
			'entreprise_id' => 0,
			'user_type_id' => Auth::user()->user_type_id,
			'addresse_livraison_client' => $request->addresse_livraison_client,
			'paiement_id' => 0,
			'num_commande' => $num_cmd,
			'bon_commande' => $facture_client,
			'facture' => $facture_client,
			'list_promo' => $panier_entreprise['list_promo'],
			'commentaire_client' => $panier_entreprise['commentaire']
		]);
		return [
			'num_cmd' => $num_cmd,
			'prix_total' => $facture_client['total']
		];
	}

	/**
	 * CHECKS - TOOLS
	 */

	/**
	 * Verification de tous les status des bon de commande entreprise dans le bon de commande client.
	 * Si toutes les entreprises correspondent a un statut de fin de commande, le statut de la commande client passera en statut_check
	 * @param $client_cmd
	 */
	private function checkAllClientCmdStatutForGlobaleStatut(Facture $client_fact)
	{
		$cancelled = true;

		foreach ($client_fact['bon_commande']['entreprises'] as $i => $entreprise_cmd) {
			foreach ($entreprise_cmd['paniers'] as $j => $panier) {
				if ($client_fact['bon_commande']['entreprises'][$i]['paniers'][$j]['statut'] != 'TERMINE' &&
					$client_fact['bon_commande']['entreprises'][$i]['paniers'][$j]['statut'] != 'ANNULE' )
					$cancelled = false;
			}
		}

		if ($cancelled) {
			//Meme si l'une des entreprise a annuler sa commande on considere que la prise en charge du bon de commande client est terminé
			//Une commande client est considérée comme terminée a partir du moment ou un statut de fin lui a etait assigné TERMINE/ANNULE
			//Si le client veut plus d'informations il pourra consulter les differentes commandes entreprises et leurs statuts.
			$tmp_cmd_client = $client_fact->bon_commande;
			$tmp_cmd_client['statut'] = 'TERMINE';
			$client_fact->bon_commande = $tmp_cmd_client;

			$tmp_fact_client = $client_fact->facture;
			$tmp_fact_client['statut'] = 'TERMINE';
			$client_fact->facture = $tmp_fact_client;

			$client_fact->statut = 'TERMINE';
			$client_fact->save();
		}
	}

	/**
	 * Verification du nouveau statut qui sera attribuer a un bon de commande sur la demande d'une entreprise
	 * (peut servir de verification lors du changement par un tier au statut TERMINE)
	 * @param $request_statut
	 * @param $actual_cmd_statut
	 */
	private function checkUpdateStatutCmd($request_statut, $actual_cmd_statut)
	{
		//impossible de modifier le statut d'une facture et cmd en 'EN_ATTENTE'
		if ($request_statut == 'EN_ATTENTE')
			abort(400, 'Statut invalide.');
		else if (($request_statut == 'ACCEPTER' || $request_statut == 'ANNULE') && $actual_cmd_statut != 'EN_ATTENTE')
			abort(400, 'Statut invalide.');
		else if ($request_statut == 'EN_COURS' && $actual_cmd_statut != 'ACCEPTER')
			abort(400, 'Statut invalide.');
		else if ($request_statut == 'TERMINE' && $actual_cmd_statut != 'EN_COURS')
			abort(400, 'Statut invalide.');
	}

	/**
	 * Verification avant paiement que la facture a bien le statut " EN_ATTENTE " (de paiement)
	 * @param $num_cmd
	 * @return bool
	 */
	public function checkFactStatutBeforePaiement($num_cmd)
	{
		if ($this->getFactByNum($num_cmd)['facture']->statut == 'EN_ATTENTE')
			return true;
		return false;
	}

	/**
	 * Verification avant paiement que les produits de la facture sont en stock suffisant
	 * @param $num_cmd
	 * @return bool
	 */
	public function checkStockFactForPaiement($num_cmd)
	{
		$list_produits_facture = $this->getFactByNum($num_cmd)['facture']['facture'];
		$tmp_fact_produits = [];

		$valide = false;
		foreach ($list_produits_facture['entreprises'] as $entreprise)
		{
			$tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['entreprise_infos'] =
				[
					'entreprise_nom' => $entreprise['entreprise_infos']['nom_enseigne'],
					'entreprise_ville' => $entreprise['entreprise_infos']['ville']['nom']
				];
			$tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['list_produits'] = [];
			foreach ($entreprise['paniers'] as $bon_commande)
			{
				foreach ($bon_commande['list_produits'] as $produit)
				{
					$tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['list_produits'][sizeof($tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['list_produits'])] = $produit;
				}
			}
		}
		foreach ($tmp_fact_produits as $bon_commande)
		{
			$entreprise = app('App\Http\Controllers\EntrepriseInformationsController')->getPublicEntrepriseInfosByName($bon_commande['entreprise_infos']['entreprise_ville'], $bon_commande['entreprise_infos']['entreprise_nom']);
			$entreprise_produits_list = app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseProduitsListByID($entreprise->id);

			foreach ($bon_commande['list_produits'] as $produit)
			{
				$valide = false;
				foreach ($entreprise_produits_list as $ent_produit)
				{
					if ($produit['ref_produit'] === $ent_produit['ref_produit'])
					{
						foreach ($ent_produit['stocks'] as $stock_entreprise) {
							if ($produit['stocks']['id'] == $stock_entreprise['id'])
							{
								//Dans le check du stock_id verifier que le stock est bien actif, > 0
								if ($stock_entreprise['activer'] && $stock_entreprise['stock'] >= $produit['quantite'])
								{
									$valide = true;
									break;
								} else
									abort(400, "Le produit " . $produit['nom'] . " de l'entreprise " . $entreprise->nom_enseigne . ' est épuisé.');
							}
						}
						break;
					}
				}
				if ($valide == false)
					return false;
			}
		}

		return true;
	}

	/**
	 * Verification que tous les produits envoyés dans la facture apartiennent bien a l'entreprise
	 * @param $list_produits
	 * @param $entreprise_produits_list
	 * @return bool
	 */
	public function checkProduitsList($list_produits, $entreprise_produits_list, $entreprise_nom)
	{
		foreach ($list_produits as $produit)
		{
			foreach ($entreprise_produits_list as $i => $ent_produit)
			{
				if ($produit['id_produit'] === $ent_produit['id'])
				{
					foreach ($ent_produit['stocks'] as $stock_entreprise)
					{
						if ($produit['stocks']['id'] == $stock_entreprise['id'])
						{
							//Dans le check du stock_id verifier que le stock est bien actif, > 0
							if ($stock_entreprise['activer'] && $stock_entreprise['stock'] >= $produit['quantite'])
								break;
							else
								abort(400, "Le produit " . $produit['nom'] . " de l'entreprise " . $entreprise_nom . ' est incorrecte ou épuisé.');
						}
					}
					break;
				}
			}
			return false;
		}
		return true;
	}

	/**
	 * Creation d'une liste de produits en se basant sur les informations de l'entreprise decoupés en fonction du poids max autorisé
	 * @param $list_produits
	 * @param $entreprise_produits_list
	 */
	private function decoupeProduitsPoidsVolume($list_produits, $entreprise_produits_list)
	{
		$decoupe_prodtuis = [];
		$index_panier = 0;
		//POIDS
		$poids_panier = 0;
		$poids_produit = 0;
		$limite_poids = 13000;//(gr)

		//VOLUME
		$volume_panier = 0;
		$volume_produit = 0;
		$limite_volume = 39000;//(cm3)

		$decoupe_prodtuis[0] = [];
		foreach ($list_produits as $produit)
		{
			foreach ($entreprise_produits_list as $ent_produit)
			{
				if ($produit['id_produit'] === $ent_produit['id'])
				{
					foreach ($ent_produit['stocks'] as $stock_entreprise)
					{
						if ($produit['stocks']['id'] == $stock_entreprise['id'])
						{
							$volume_produit += $produit['quantite'] * $stock_entreprise['volume'];
							$poids_produit = $produit['quantite'] * $stock_entreprise['poids'];
							//quantite trop grande pour etre stocker dans le sac en cour
							if (($poids_panier + $poids_produit) > $limite_poids)
							{
								//calcule de la quantite maximum pouvant etre mise dans un sac
								$max_quantite_produit = (int)($limite_poids / $stock_entreprise['poids']);
								//peut on combler le sac actuel avec un peut de quantite du produit trop lourd
								$max_quantite_produit_sac_actuel = (int)(($limite_poids - $poids_panier) / $stock_entreprise['poids']);
								if ($max_quantite_produit_sac_actuel >= 1)
								{
									array_push($decoupe_prodtuis[$index_panier], [
										'nom' => $ent_produit['nom'],
										'type' => $ent_produit['type'],
										'marque' => $ent_produit['marque'],
										'quantite' => $max_quantite_produit_sac_actuel,
										'stocks' => $stock_entreprise,
										'famille' => $ent_produit['famille']['nom'],
										'categorie' => $ent_produit['categorie']['nom'],
										'description' => $ent_produit['description'],
										'ref_produit' => $ent_produit['ref_produit'],
										'path_file_photo_principale' => $ent_produit['path_file_photo_principale'],
										'path_file_photos_secondaire' => $ent_produit['path_file_photos_secondaire'],
									]);
									$produit['quantite'] -= $max_quantite_produit_sac_actuel;
								}
								$poids_panier = 0;
								$volume_produit = 0;
								++$index_panier;
								$decoupe_prodtuis[$index_panier] = [];
								while ($produit['quantite'] > $max_quantite_produit)
								{
									if ($produit['quantite'] >= $max_quantite_produit)
									{
										array_push($decoupe_prodtuis[$index_panier], [
											'nom' => $ent_produit['nom'],
											'type' => $ent_produit['type'],
											'marque' => $ent_produit['marque'],
											'quantite' => $max_quantite_produit_sac_actuel,
											'stocks' => $stock_entreprise,
											'famille' => $ent_produit['famille']['nom'],
											'categorie' => $ent_produit['categorie']['nom'],
											'description' => $ent_produit['description'],
											'ref_produit' => $ent_produit['ref_produit'],
											'path_file_photo_principale' => $ent_produit['path_file_photo_principale'],
											'path_file_photos_secondaire' => $ent_produit['path_file_photos_secondaire'],
										]);
										$produit['quantite'] -= $max_quantite_produit_sac_actuel;
										++$index_panier;
										$decoupe_prodtuis[$index_panier] = [];
									}
								}
							}
							elseif (($volume_panier + $volume_produit) > $limite_volume)
							{
								//calcule de la quantite maximum pouvant etre mise dans un sac
								$max_quantite_produit = (int)($limite_volume / $stock_entreprise['volume']);
								//peut on combler le sac actuel avec un peut de quantite du produit trop lourd
								$max_quantite_produit_sac_actuel = (int)(($limite_volume - $volume_panier) / $stock_entreprise['volume']);
								if ($max_quantite_produit_sac_actuel >= 1) {
									array_push($decoupe_prodtuis[$index_panier], [
										'nom' => $ent_produit['nom'],
										'type' => $ent_produit['type'],
										'marque' => $ent_produit['marque'],
										'quantite' => $max_quantite_produit_sac_actuel,
										'stocks' => $stock_entreprise,
										'famille' => $ent_produit['famille']['nom'],
										'categorie' => $ent_produit['categorie']['nom'],
										'description' => $ent_produit['description'],
										'ref_produit' => $ent_produit['ref_produit'],
										'path_file_photo_principale' => $ent_produit['path_file_photo_principale'],
										'path_file_photos_secondaire' => $ent_produit['path_file_photos_secondaire'],
									]);
									$produit['quantite'] -= $max_quantite_produit_sac_actuel;
								}
								$poids_panier = 0;
								$volume_produit = 0;
								++$index_panier;
								$decoupe_prodtuis[$index_panier] = [];
								while ($produit['quantite'] > $max_quantite_produit)
								{
									if ($produit['quantite'] >= $max_quantite_produit) {
										array_push($decoupe_prodtuis[$index_panier], [
											'nom' => $ent_produit['nom'],
											'type' => $ent_produit['type'],
											'marque' => $ent_produit['marque'],
											'quantite' => $max_quantite_produit_sac_actuel,
											'stocks' => $stock_entreprise,
											'famille' => $ent_produit['famille']['nom'],
											'categorie' => $ent_produit['categorie']['nom'],
											'description' => $ent_produit['description'],
											'ref_produit' => $ent_produit['ref_produit'],
											'path_file_photo_principale' => $ent_produit['path_file_photo_principale'],
											'path_file_photos_secondaire' => $ent_produit['path_file_photos_secondaire'],
										]);
										$produit['quantite'] -= $max_quantite_produit_sac_actuel;
										++$index_panier;
										$decoupe_prodtuis[$index_panier] = [];
									}
								}
							}
							else
								{
									$poids_panier += $poids_produit;
									$volume_panier += $volume_produit;
								}
							array_push($decoupe_prodtuis[$index_panier], [
								'nom' => $ent_produit['nom'],
								'type' => $ent_produit['type'],
								'marque' => $ent_produit['marque'],
								'quantite' => $produit['quantite'],
								'stocks' => $stock_entreprise,
								'famille' => $ent_produit['famille']['nom'],
								'categorie' => $ent_produit['categorie']['nom'],
								'description' => $ent_produit['description'],
								'ref_produit' => $ent_produit['ref_produit'],
								'path_file_photo_principale' => $ent_produit['path_file_photo_principale'],
								'path_file_photos_secondaire' => $ent_produit['path_file_photos_secondaire'],
							]);
							break;
						}
					}
					break;
				}
			}
		}
		return $decoupe_prodtuis;
	}

	/**
	 * Filtre des commande pour l'affichage entreprise des commande payées
	 * @return array
	 */
	private function filterListCommandesPaye()
	{
		$entreprise_id = app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId();
		$bons_commande = Facture::where(['entreprise_id' => $entreprise_id])->get();

		$filter_bon_commande = [];
		foreach ($bons_commande as $i => $bon_commande)
			$filter_bon_commande[$i] = $bon_commande;

		return $filter_bon_commande;
	}

	/**
	 * Genreation des données de facturation
	 * @param Request $request
	 * @param $title
	 */
	private function generateFacture($facture, $title, $list_produits, $other_datas = [])
	{
		$facture_infos = [
			'title' => $title,
			'client_infos' => $facture->client,
			'entreprise_infos' => $facture->entreprise,
			'num_commande' => $facture->num_commande,
			'user_type' => $facture->userType['nom'],
			'list_promo' => $facture->list_promo,
			'livraison' => $other_datas['livraison'],
			'commentaire_client' => $facture->commentaire_client,
			'information_complémentaire' => $other_datas['information_complémentaire'],
			'commentaire_entreprise' => '',
			'addresse_livraison_client' => $facture->addresse_livraison_client,
			'date' => Carbon::parse($facture->created_at)->toDateTimeString(),
			//prendre en compte dans le calcule du total la list des promos lorsqu'elles seront implementées
			'total_facture' => $this->calculTotalFact($list_produits, $other_datas['livraison']),
			'list_produits' => $list_produits,
			'statut' => $facture->statut,
			'rembourse' => false,
			'rembourse_infos' => [],
		];

		if ($title == "Bon de commande") {
			$facture_infos['total_produits'] = sizeof($list_produits);
			//date de livraison a definir avec le retour de Ortoo sur la prise en charge de la cmd par un coursier
			$facture_infos['date_livraison'] = $other_datas['date_livraison'];
			$facture_infos['ref_ortoo'] = '';
		}

		return $facture_infos;
	}

	/**
	 * Calcule du total de la facture en se base sur l'ajout du resultat quantité_produit * prix_ttc_produit
	 * @param $list_produits
	 * @return float|int
	 */
	private function calculTotalFact($list_produits, $livraison)
	{
		$total_fact = 0;

		foreach ($list_produits as $produit) {
			$total_fact += $produit['quantite'] * $produit['stocks']['prix'];
		}

		if ($livraison)
			$total_fact += 2.90;

		return $total_fact;
	}

	/**
	 * UPDATE PANIER
	 */

	/**
	 * Mise a jour des informations du panier client, check de la validité des produits,
	 * recalcule des totaux de chaques entreprises, decoupage des produit en fonction de leurs poids et/ou volume
	 * @param Request $request
	 * @return mixed
	 */
	public function updatePanierInformations(Request $request)
	{
		$total_montant_entreprises = 0;
		$total_livraisons_entreprises = 0;
		$entreprise_ferme = false;
		$liste_entreprises_ferme = '';

		foreach ($request->paniers as $panier_entreprise)
		{
			$entreprise = app('App\Http\Controllers\EntrepriseInformationsController')->getPublicEntrepriseInfosByName($panier_entreprise["shop"]['city'], $panier_entreprise["shop"]['name']);
			if ($entreprise->status == 'OUVERT') {
				$entreprise_produits_list = app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseProduitsListByID($entreprise->id);

				//Verification que les produits existent bien dans l'entreprise ciblée
				$this->checkProduitsList($panier_entreprise['product'], $entreprise_produits_list, $entreprise->nom_enseigne);

				$list_paniers_produits = $this->decoupeProduitsPoidsVolume($panier_entreprise['product'], $entreprise_produits_list);

				$total_montant_entreprise = 0;
				$nb_livraison_entreprise = 0;

				//Information entreprise
				foreach ($list_paniers_produits as $list_produits) {
					$total_montant_entreprise += $this->calculTotalFact($list_produits, $request->livraison);
					$nb_livraison_entreprise += 1;
				}
				//Informations generales
				$total_montant_entreprises += $total_montant_entreprise;
				$total_livraisons_entreprises += $nb_livraison_entreprise;

				$datas['entreprises'][$panier_entreprise["shop"]['name']] =
					[
						'total_montant_entreprise' => $total_montant_entreprise,
						'nb_livraison_entreprise' => $nb_livraison_entreprise,
					];
			} else {
				$entreprise_ferme = true;
				$liste_entreprises_ferme = !empty($liste_entreprises_ferme) ? $liste_entreprises_ferme . ', ' . $entreprise->nom_enseigne : $entreprise->nom_enseigne;
			}
		}

		$datas['total_montant_entreprises'] = $total_montant_entreprises;
		$datas['total_livraisons_entreprises'] = $total_livraisons_entreprises;

		return
			[
				'panier' => $datas,
				'modification' => $entreprise_ferme,
				'message' => $entreprise_ferme ? 'Les entreprises ' . $liste_entreprises_ferme . ' ont fermé, votre panier a était mis a jour.' : ''
			];
	}

	/**
	 * UPDATE D'UN BON DE COMMANDE
	 */

	/**
	 * Mise a jour des informations lie au bon de commande de l'entreprise
	 * @param Request $request
	 */
	public function updateEntrepriseCommandeStatut(Request $request)
	{
		if (empty($facture = $this->getFactByNum($request->num_commande)))
			abort(400, 'Facture non trouvée.');

		$this->checkUpdateStatutCmd($request->statut, $facture['facture']->statut);

		$tmp_cmd = $facture['facture']->bon_commande;
		$tmp_fact = $facture['facture']->facture;

		if ($request->statut == 'EN_COURS')
		{
			$user_id = app('App\Http\Controllers\ClientInformationsController')->getUserIdFromClientId($facture['facture']->client_id);
			$client_mail = app('App\Http\Controllers\UserInformationsController')->getUserInstance($user_id)->email;
			if ($facture['facture']->bon_commande['livraison'])
			{
				//Creation d'une liaison entre un bon de commande d'une entreprise et ortoo
				$this->createOrtooCourse($facture['facture']->bon_commande['num_commande'], $client_mail);
			}
			else
			{
				//Envoie du mail de confirmation de la livraison lancee au client
				$mail_fact = new CustomMail();
				$mail_fact->setAddressTo($client_mail);
				$mail_fact->setAddressFrom([config('mail.from.address'), config('mail.from.name')]);
				$mail_fact->setSubject('Retrait de votre commande.');
				$mail_fact->setViewTemplate('emails.mailConfirmLivraison');
				$mail_fact->setTemplateDatas([
					'ref_ortoo' => "Pas de référence (RETRAIT)",
					'num_cmd' => $facture['facture']->bon_commande['num_commande']
				]);
				$request->statut = 'TERMINE';
			}
		}
		else if ($request->statut == 'ANNULE')
		{
			app('App\Http\Controllers\PaiementController')->remboursement($request->num_commande, $request->annulation_commentaire);

			$tmp_cmd['commentaire_entreprise'] = $request->annulation_commentaire;
			$tmp_fact['commentaire_entreprise'] = $request->annulation_commentaire;
			$facture['facture']->commentaire_entreprise = $request->annulation_commentaire;
		}

		$this->updateClientCommandeStatut($request->num_commande, $facture['facture']->client_id, $request->statut);

		$facture['facture']->statut = $request->statut;

		$tmp_cmd['statut'] = $request->statut;
		$facture['facture']->bon_commande = $tmp_cmd;

		$tmp_fact['statut'] = $request->statut;
		$facture['facture']->facture = $tmp_fact;

		$facture['facture']->save();

		abort(200, 'La commande a etait mise a jour.');
	}

	/**
	 * Mise a jour du statut du bon de commande et de l'objet facture en db
	 * @param $num_cmd
	 * @param $client_id
	 * @param $cmd_status
	 */
	private function updateClientCommandeStatut($num_cmd, $client_id, $cmd_status)
	{
		$bon_commande_client_modifier = $this->getClientCmdFrom($num_cmd, $client_id);

		if ($bon_commande_client_modifier)
		{
			if (empty($bon_commande_client = Facture::select('bon_commande', 'facture')->where([
				'client_id' => $client_id,
				'entreprise_id' => 0,
				'num_commande' => $bon_commande_client_modifier['num_commande']
			])->first()))
				abort(400, 'Facture client non trouvée.');
			$tmp_bon_commande = $bon_commande_client->bon_commande;
			$tmp_facture = $bon_commande_client->facture;

			foreach ($tmp_bon_commande['entreprises'] as $i => $entreprise) {
				foreach ($entreprise['paniers'] as $j => $panier) {
					if ($panier['num_commande'] == $num_cmd) {
						$tmp_bon_commande['entreprises'][$i]['paniers'][$j]['statut'] = $cmd_status;
						$tmp_facture['entreprises'][$i]['paniers'][$j]['statut'] = $cmd_status;
						break;
					}
				}
			}

			Facture::where([
				'client_id' => $client_id,
				'entreprise_id' => 0,
				'num_commande' => $bon_commande_client_modifier['num_commande']
			])->update([
				'bon_commande' => json_encode($tmp_bon_commande),
				'facture' => json_encode($tmp_facture)
			]);
		}
		//Une fois le statut du bon de commande du client actualisé, ckeck pour savoir si le statut de la commande entiere peut etre validé
		$this->checkAllClientCmdStatutForGlobaleStatut($bon_commande_client);
	}

	/**
	 * Mise a jour des stocks et statuts des bon de commande et facture du clients et des entreprises
	 * se trouvant dans le panier du client
	 * @param $client_num_cmd
	 */
	public function updateStockAndStatutAfterPaiement($client_num_cmd)
	{
		if (empty($facture = $this->getFactByNum($client_num_cmd)['facture']))
			abort(400, 'Facture non trouvée.');

		//UpdateClient
		$this->updateFactCmdStatut($client_num_cmd);

		$list_produits_facture = $facture['facture'];
		$tmp_fact_produits = [];

		//Preparation infos entreprise depuis le client
		foreach ($list_produits_facture['entreprises'] as $i => $entreprise) {
			$tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['entreprise_infos'] = [
				'entreprise_nom' => $entreprise['entreprise_infos']['nom_enseigne'],
				'entreprise_ville' => $entreprise['entreprise_infos']['ville']['nom']
			];
			$tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['list_produits'] = [];
			foreach ($entreprise['paniers'] as $j => $bon_commande) {
				foreach ($bon_commande['list_produits'] as $produit) {
					$tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['list_produits'][sizeof($tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['list_produits'])] = $produit;
					$tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['list_produits'][sizeof($tmp_fact_produits[$entreprise['entreprise_infos']['nom_enseigne']]['list_produits']) - 1]['num_commande'] = $bon_commande["num_commande"];
				}
			}
		}

		//Update entreprise
		foreach ($tmp_fact_produits as $bon_commande) {
			foreach ($bon_commande['list_produits'] as $produit) {
				app('App\Http\Controllers\EntrepriseInformationsController')
					->updateStockAfterPaiementEntreprise(
						[
							'entreprise_nom' => $bon_commande['entreprise_infos']['entreprise_nom'],
							'ville_nom' => $bon_commande['entreprise_infos']['entreprise_ville'],
							'ref_produit' => $produit['ref_produit'],
							'id_stock' => $produit['stocks']['id'],
							'quantite' => $produit['quantite']
						]);
				$this->updateFactCmdStatut($produit['num_commande']);
			}
		}
	}

	/**
	 * Modification du statut etant dans les templates bon de commande et facture EN_ATTENTE_DE_PAIEMENT en PAYE
	 * @param $num_cmd
	 */
	private function updateFactCmdStatut($num_cmd)
	{
		if (empty($facture = Facture::where([
			'num_commande' => $num_cmd
		])->first()))
			abort(400, 'Facture non trouvée.');

		$tmp_cmd = $facture->bon_commande;
		$tmp_cmd['statut'] = "PAYE";
		$tmp_fact = $facture->facture;
		$tmp_fact['statut'] = "PAYE";

		$facture->bon_commande = $tmp_cmd;
		$facture->facture = $tmp_fact;

		$list_produits_facture = $facture['facture'];
		$list_produits_commande = $facture['bon_commande'];
		//CLIENT
		if (isset($list_produits_facture['entreprises'])) {
			foreach ($list_produits_facture['entreprises'] as $i => $entreprise)
				foreach ($entreprise['paniers'] as $j => $bon_commande)
					$list_produits_facture['entreprises'][$i]['paniers'][$j]['statut'] = "PAYE";

			foreach ($list_produits_commande['entreprises'] as $i => $entreprise)
				foreach ($entreprise['paniers'] as $j => $bon_commande)
					$list_produits_commande['entreprises'][$i]['paniers'][$j]['statut'] = "PAYE";
		} //ENTREPRISE
		else {
			$list_produits_facture['statut'] = "PAYE";
			$list_produits_commande['statut'] = "PAYE";
		}
		$facture['facture'] = $list_produits_facture;
		$facture['bon_commande'] = $list_produits_commande;
		$facture->save();
	}

	/**
	 * Mise a jour des bon du bon de commande client sur une annulation (ajout du commentaire d'annulation)
	 * @param $entreprise_num_cmd
	 * @param $client_id
	 * @param $annulation_commentaire
	 */
	public function updateAnnulationCmd($entreprise_num_cmd, $client_id, $annulation_commentaire)
	{
		$bon_commande_client = $this->getClientCmdFrom($entreprise_num_cmd, $client_id);
		$facture_client = Facture::where(['num_commande' => $bon_commande_client['num_commande']])->first();
		$tmp_cmd_client = $facture_client->bon_commande;
		$tmp_fact_client = $facture_client->facture;

		foreach ($facture_client->bon_commande['entreprises'] as $i => $entreprise) {
			foreach ($entreprise['paniers'] as $j => $panier) {
				if ($panier['num_commande'] == $entreprise_num_cmd) {
					$tmp_cmd_client['entreprises'][$i]['paniers'][$j]['commentaire_entreprise'] = $annulation_commentaire;
					$tmp_fact_client['entreprises'][$i]['paniers'][$j]['commentaire_entreprise'] = $annulation_commentaire;
					break;
				}
			}
		}
		$facture_client->commentaire_entreprise = $annulation_commentaire;
		$facture_client->bon_commande = $tmp_cmd_client;
		$facture_client->facture = $tmp_fact_client;
		$facture_client->save();
	}

	/**
	 * Mise a jour de informations d'une entreprise et d'un client suite au remboursement d'une annulation
	 * @param $cmd_client
	 * @param $cmd_entreprise
	 * @param $paiement_id
	 */
	public function updateFactCmdAnnulation($cmd_client, $cmd_entreprise, $paiement_id)
	{
		$client_fact = Facture::where(['num_commande' => $cmd_client])->first();
		$entreprise_fact = Facture::where(['num_commande' => $cmd_entreprise])->first();

		if (empty($client_fact) || empty($client_fact))
			abort(400, 'Bon de commande introuvable.');

		$tmp_client_fact = $client_fact->facture;
		$tmp_client_cmd = $client_fact->bon_commande;

		$tmp_entreprise_fact = $entreprise_fact->facture;
		$tmp_entreprise_cmd = $entreprise_fact->bon_commande;

		$entreprise_fact->statut = 'ANNULE';

		foreach ($client_fact['bon_commande']['entreprises'] as $i => $entreprise_cmd) {
			foreach ($entreprise_cmd['paniers'] as $j => $panier) {
				if ($panier['num_commande'] == $cmd_entreprise) {
					$tmp_client_cmd['entreprises'][$i]['paniers'][$j]['rembourse'] = true;
					$tmp_client_cmd['entreprises'][$i]['paniers'][$j]['statut'] = 'ANNULE';
					$tmp_client_cmd['entreprises'][$i]['paniers'][$j]['rembourse_infos'][$paiement_id] = ['id' => $paiement_id];

					$tmp_client_fact['entreprises'][$i]['paniers'][$j]['rembourse'] = true;
					$tmp_client_fact['entreprises'][$i]['paniers'][$j]['statut'] = 'ANNULE';
					$tmp_client_fact['entreprises'][$i]['paniers'][$j]['rembourse_infos'][$paiement_id] = ['id' => $paiement_id];
				}
			}
		}

		$tmp_entreprise_fact['rembourse'] = true;
		$tmp_entreprise_fact['rembourse_infos'][$paiement_id] = ['id' => $paiement_id];
		$tmp_entreprise_cmd['rembourse'] = true;
		$tmp_entreprise_cmd['rembourse_infos'][$paiement_id] = ['id' => $paiement_id];

		$client_fact->facture = $tmp_client_fact;
		$client_fact->bon_commande = $tmp_client_cmd;
		$entreprise_fact->facture = $tmp_entreprise_fact;
		$entreprise_fact->bon_commande = $tmp_entreprise_cmd;

		$client_fact->save();
		$entreprise_fact->save();

		$this->checkAllClientCmdStatutForGlobaleStatut($client_fact);
	}

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation du totale d'une facture client pour proceder au paiement
	 * @param $num_cmd
	 * @return mixed
	 */
	public function getTotalFacture($num_cmd)
	{
		return $this->getFactByNum($num_cmd)['facture']['facture']['total'];
	}

	/**
	 * Recuperation de la liste des factures de l'entreprise ou du client
	 * @return mixed
	 */
	public function getFactureList()
	{
		if (Auth::user()->isClient()) {
			//Precision d'une entreprise_id = 0 pour les factures client pouvant regrouper plusieurs entreprises sur une facture (resultat de tout le panier)
			$client_id = json_decode(app('App\Http\Controllers\ClientInformationsController')->showClientInformations()->getContent())->id;
			return Facture::where([
				'client_id' => $client_id,
				'entreprise_id' => 0,
			])->get();
			//ajouter une condition a la requete sur le token_id referent d'un token de paiement permettant aux clients de pouvoir visualiser uniquement les factures quand elles sont reglées
		} elseif (Auth::user()->isEntreprise()) {
			$entreprise_id = app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId();
			return Facture::where(['entreprise_id' => $entreprise_id])->get();
		}
	}

	/**
	 * Recuperation de la facture par son numero de commande
	 * @param $num_cmd
	 * @return mixed
	 */
	public function getFactByNum($num_cmd)
	{
		if (Auth::user()->isClient()) {
			$client_id = json_decode(app('App\Http\Controllers\ClientInformationsController')->showClientInformations()->getContent())->id;
			if (empty($facture = Facture::where([
				'client_id' => $client_id,
				'entreprise_id' => 0,
				'num_commande' => $num_cmd
			])->first()))
				abort(400, 'Facture non trouvée');

			return
				[
					'facture' => $facture,
					'template' => 'facturation.facture_client'
				];
		} elseif (Auth::user()->isEntreprise()) {
			$entreprise_id = app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId();
			if (empty($facture = Facture::where([
				'entreprise_id' => $entreprise_id,
				'num_commande' => $num_cmd
			])->first()))
				abort(400, 'Facture non trouvée');
			return
				[
					'facture' => $facture,
					'template' => 'facturation.facture_entreprise'
				];
		}
		abort(400, 'Acces a cette facture non authorisé');
	}

	/**
	 * Recuperation de la liste des bons de commande
	 * @return mixed
	 */
	public function getCommandesList()
	{
		if (Auth::user()->isClient()) {
			$client_id = json_decode(app('App\Http\Controllers\ClientInformationsController')->showClientInformations()->getContent())->id;
			return Facture::select('bon_commande')->where([
				'client_id' => $client_id,
				'entreprise_id' => 0,
			])->get();
		} elseif (Auth::user()->isEntreprise()) {
			return $this->filterListCommandesPaye();
		}
	}

	/**
	 * Recuperation d'un bon de commande client en fonction d'un numero de commande entreprise
	 * @param $entreprise_num_cmd
	 * @param $client_id
	 * @return mixed
	 */
	public function getClientCmdFrom($entreprise_num_cmd, $client_id)
	{
		if (empty($bon_commandes = Facture::select('bon_commande')->where([
			'client_id' => $client_id,
			'entreprise_id' => 0
		])->get()))
			abort(400, 'Facture non trouvée.');

		foreach ($bon_commandes as $i => $commande) {
			foreach ($commande['bon_commande']['entreprises'] as $j => $entreprise) {
				foreach ($entreprise['paniers'] as $k => $panier) {
					if ($panier['num_commande'] == $entreprise_num_cmd) {
						return $bon_commandes[$i]['bon_commande'];
					}
				}
			}
		}
	}

	/**
	 * Recuperation du statut d'une livraison d'un bon de commande client et entreprise
	 * @param $ortoo_ref
	 * @return null|\Psr\Http\Message\ResponseInterface
	 */
	public function getLivraisonStatut($num_cmd)
	{
		if (empty($facture = $this->getFactByNum($num_cmd)))
			abort(400, 'Facture non trouvée.');

		$commandes = $facture['facture']->bon_commande;
		$livraisons = [];

		if (Auth::user()->isClient()) {
			foreach ($commandes['entreprises'] as $j => $entreprise) {
				foreach ($entreprise['paniers'] as $k => $panier) {
					if (!empty($panier['ref_ortoo'])) {
						$livraisons[$panier['num_commande']] = [
							'num_cmd' => $panier['num_commande'],
							'vendor_name' => $j,
							'refcourse' => $panier['ref_ortoo'],
							'course_details' => $this->getCourseDetails($panier['ref_ortoo']),
						];
					}
				}
			}
		} else if (Auth::user()->isEntreprise()) {
			$livraisons[$commandes['num_commande']] = [
				'num_cmd' => $commandes['num_commande'],
				'vendor_name' => $commandes['entreprise_infos']['nom_enseigne'],
				'refcourse' => $commandes['ref_ortoo'],
				'course_details' => $this->getCourseDetails($commandes['ref_ortoo']),
			];
		}
		return $livraisons;
	}

	/**
	 * Recuperation des informations d'une course par ortoo
	 * @param $ortoo_ref
	 * @return null|\Psr\Http\Message\ResponseInterface
	 */
	private function getCourseDetails($ortoo_ref)
	{
		$http = new guzzleClient([
			'base_uri' => 'https://api.tassodelivery.com/',
			'headers' =>
				[
					'Accept' => 'application/json',
					'Content-Type' => 'application/json'
				]
		]);

		try {
			$response = $http->post('/Course/Details',
				[
					'form_params' =>
						[
							"public_key" => config('services.ortoo.key'),
							"refcourse" => $ortoo_ref
						]
				]);
		} catch (ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			abort(400, "Echec de la demande d'informations de la course: " . $responseBodyAsString);
		}

		return json_decode($response->getBody()->getContents(), true);
	}

	/**
	 * Recuperation des informations de paiement d'un client en se servant d'un numero de commande et de son id
	 * @param $client_num_cmd
	 * @param $client_id
	 * @return mixed
	 */
	public function getClientPaimentIdFor($client_num_cmd, $client_id)
	{
		if (empty($facture = Facture::where([
			'client_id' => $client_id,
			'entreprise_id' => 0,
			'num_commande' => $client_num_cmd
		])->first()))
			abort(400, 'Facture non trouvée');
		return $facture->paiement_id;
	}

	/**
	 * AFFICHAGE ET TELECHARGEMENT FACTURES
	 */

	/**
	 * Affichage de la facture en se basant sur le numero de la commande (policy sur token_id et client_id ou entreprise_id)
	 * @param $num_cmd
	 * @return $this
	 */
	public function displayHTMLFact($num_cmd)
	{
		$facture = $this->getFactByNum($num_cmd);

		return view($facture['template'])->with([
			'title' => 'Facture',
			'facture' => $facture['facture']['facture'],
			'impression' => false,
		]);
	}

	/**
	 * Affichage du bon de commande en se basant sur le numero de la commande (policy sur client_id ou entreprise_id)
	 * @param $num_cmd
	 * @return $this
	 */
	public function displayHTMLCMD($num_cmd)
	{
		$facture = $this->getFactByNum($num_cmd);

		return view($facture['template'])->with([
			'title' => 'Bon de commande',
			'facture' => $facture['facture']['bon_commande'],
			'impression' => false,
		]);
	}

	/**
	 * Retour du fichier d'une facture au format PDF pour le telechargement du document coté client
	 * @param $num_cmd
	 * @return mixed
	 */
	public function downloadPDFFact($num_cmd)
	{
		if (empty($facture = $this->getFactByNum($num_cmd)))
			abort(400, 'Facture non trouvée');

		return \PDF::loadView($facture['template'],
			[
				'title' => 'Facture',
				'facture' => $facture['facture']['facture'],
				'impression' => true
			])
			->setPaper('a4', 'portrait')
			->setWarnings(false)
			->download('facture-' . $num_cmd . '.pdf');
	}

	/**
	 * Telchargement d'un justificatif d'annulation d'une commande
	 * @param $num_cmd
	 * @return mixed
	 */
	public function downloadJustifCmd($num_cmd)
	{
		if (empty($facture = $this->getFactByNum($num_cmd)))
			abort(400, 'Facture non trouvée');

		if ($facture['facture']->statut != "ANNULE")
			abort(400, "Cette facture n'est pas annulé");

		$pdf = \PDF::loadView('facturation.annulationCommande', [
			'num_cmd' => $facture['facture']->bon_commande['num_commande'],
			'date_creation' => $facture['facture']->created_at,
			'date_annulation' => $facture['facture']->updated_at,
			'annulation_commentaire' => $facture['facture']->bon_commande['commentaire_entreprise'],
		])
			->setPaper('a4', 'portrait')
			->setWarnings(false);

		return $pdf->download('justificatif_annulation-' . $num_cmd . '.pdf');
	}

	/**
	 * ORTOO
	 * getenv('ORTOO_TEST_KEY') et getenv('ORTOO_PUBLIC_KEY') setter dans le .env
	 */

	/**
	 * CREATE
	 */

	/**
	 * Creation d'une course Ortoo et linkage de la reference de la course au bon de commande
	 * @param Request $request
	 * @return null|\Psr\Http\Message\ResponseInterface
	 */
	private function createOrtooCourse($num_commande, $client_mail)
	{
		if (empty($facture = $this->getFactByNum($num_commande)))
			abort(400, 'Facture non trouvée.');

		$http = new guzzleClient([
			'base_uri' => 'https://api.tassodelivery.com/',
			'headers' =>
				[
					'Accept' => 'application/json',
					'Content-Type' => 'application/json'
				]
		]);

		try {
			$response = $http->post('Course/Create',
				[
					'form_params' =>
						[
							"public_key" => config('services.ortoo.key'),
							"Steps" =>
								[
									[
										"address" => $facture['facture']->bon_commande['entreprise_infos']['addresse_entreprise']['addresse'] . ' ' . $facture['facture']->bon_commande['entreprise_infos']['addresse_entreprise']['code_postal'] . ' ' . (!empty($facture['facture']->bon_commande['entreprise_infos']['addresse_entreprise']['commune']) ? $facture['facture']->bon_commande['entreprise_infos']['addresse_entreprise']['commune'] : $facture['facture']->bon_commande['entreprise_infos']['addresse_entreprise']['ville']),
										"lastname" => $facture['facture']->bon_commande['entreprise_infos']['contact_entreprise']['nom'],
										"firstname" => $facture['facture']->bon_commande['entreprise_infos']['contact_entreprise']['prenom'],
										"phone" => $facture['facture']->bon_commande['entreprise_infos']['contact_entreprise']['telephone'],
										"option" => '2',
										"instruction" => $facture['facture']->bon_commande['entreprise_infos']['addresse_entreprise']['infos_addresse']
									],
									[
										"address" => !empty($facture['facture']->bon_commande['addresse_livraison_client']) ? $facture['facture']->bon_commande['addresse_livraison_client'] : ($facture['facture']->bon_commande['client_infos']['addresses_livraison'][0]['addresse'] . ' ' . $facture['facture']->bon_commande['client_infos']['addresses_livraison'][0]['code_postal'] . ' ' . !empty($facture['facture']->bon_commande['client_infos']['addresses_livraison'][0]['commune']) ? $facture['facture']->bon_commande['client_infos']['addresses_livraison'][0]['commune'] : $facture['facture']->bon_commande['client_infos']['addresses_livraison'][0]['ville']),
										"lastname" => $facture['facture']->bon_commande['client_infos']['nom'],
										"firstname" => $facture['facture']->bon_commande['client_infos']['prenom'],
										"phone" => $facture['facture']->bon_commande['client_infos']['telephone'],
										"option" => '2',
										"instruction" => $facture['facture']->bon_commande["information_complémentaire"]
									]
								]
						]
				]);

			$tmp_ortoo_ref = json_decode((string)$response->getBody(), true)['course']['refcourse'];

			$response = $http->post('Course/Launch',
				[
					'form_params' =>
						[
							"public_key" => config('services.ortoo.key'),
							"refcourse" => [$tmp_ortoo_ref]
						]
				]);
			$tmp_cmd_ent = $facture['facture']->bon_commande;
			$tmp_cmd_ent['ref_ortoo'] = $tmp_ortoo_ref;
			$facture['facture']->bon_commande = $tmp_cmd_ent;
			Facture::where(
				[
					'id' => $facture['facture']['id'],
					'num_commande' => $num_commande
				])
				->update(['bon_commande' => json_encode($facture['facture']->bon_commande)]);

			//Envoie du mail de confirmation de la livraison lancee à l'entreprise
			$mail_fact = new CustomMail();
			$mail_fact->setAddressTo(Auth::user()->email);
			$mail_fact->setAddressFrom([config('mail.from.address'), config('mail.from.name')]);
			$mail_fact->setSubject('Livraison Tasso en cours.');
			$mail_fact->setViewTemplate('emails.mailConfirmLivraison');
			$mail_fact->setTemplateDatas([
				'ref_ortoo' => $tmp_ortoo_ref,
				'num_cmd' => $num_commande
			]);
			Mail::send($mail_fact);

			//Envoie du mail de confirmation de la livraison lancee au client
			$mail_fact = new CustomMail();
			$mail_fact->setAddressTo($client_mail);
			$mail_fact->setAddressFrom([config('mail.from.address'), config('mail.from.name')]);
			$mail_fact->setSubject('Livraison Tasso en cours.');
			$mail_fact->setViewTemplate('emails.mailConfirmLivraison');
			$mail_fact->setTemplateDatas([
				'ref_ortoo' => $tmp_ortoo_ref,
				'num_cmd' => $num_commande
			]);
			Mail::send($mail_fact);

			//recuperation bon de commande client et mise a jour du champ (json) ref_ortoo
			$bon_commande_client = $this->getClientCmdFrom($facture['facture']->bon_commande['num_commande'], $facture['facture']->client_id);
			$facture_client = Facture::where(['num_commande' => $bon_commande_client['num_commande']])->first();
			$tmp_cmd_client = $facture_client->bon_commande;
			foreach ($facture_client->bon_commande['entreprises'] as $i => $entreprise) {
				foreach ($entreprise['paniers'] as $j => $panier) {
					if ($panier['num_commande'] == $facture['facture']->bon_commande['num_commande']) {
						$tmp_cmd_client['entreprises'][$i]['paniers'][$j]['ref_ortoo'] = $tmp_ortoo_ref;
						break;
					}
				}
			}
			$facture_client->bon_commande = $tmp_cmd_client;
			$facture_client->save();
		} catch (ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			abort(400, "Echec de la creation d'une course: " . $responseBodyAsString);
		}

		return $response;
	}

	/**
	 * CHECKS
	 */

	/**
 	 * Appel automatisé
	 * Modification du statut d'un bon de commande vers le statut terminé en fonction du retour de la course d'Ortoo
	 * @return string
	 */
	public function checkOrtooCourseStatut()
	{
		$factures = Facture::where(['statut' => 'EN_COURS'])
			->where('entreprise_id', '!=', 0)
			->get();

		foreach ($factures as $facture) {
			$course_infos = $this->getEntrepriseCommandStatut($facture);

			if ($course_infos[$facture['num_commande']]['course_details']['course']['termined']) {
				if (empty($facture_i = Facture::where([
					'entreprise_id' => $facture->entreprise_id,
					'statut' => 'EN_COURS',
					'num_commande' => $facture->num_commande,
				])->first()))
					echo "Impossible de mettre le statut Ortoo a jour: " . Carbon::now() . ': num_commande => ' . $facture->num_commande . '\n';

				$this->checkUpdateStatutCmd('TERMINE', $facture->statut);

				$tmp_cmd = $facture->bon_commande;
				$tmp_fact = $facture->facture;
				$this->updateClientCommandeStatut($facture->num_commande, $facture->client_id, 'TERMINE');

				$facture->statut = 'TERMINE';

				$tmp_cmd['statut'] = 'TERMINE';
				$facture->bon_commande = $tmp_cmd;

				$tmp_fact['statut'] = 'TERMINE';
				$facture->facture = $tmp_fact;

				$facture->save();
			}
		}
		echo "Rafraichissement du statut des courses effectué: " . Carbon::now() . '\n';
	}

	/**
	 * Requete a Ortoo afin de verifier que les addresses sont corretes, reconnue par google et dans la zone de livraison
	 * @param $addresse_client
	 * @param $addresse_entreprise
	 * @return array
	 */
	private function checkOrtooAddresses($addresse_client, $addresse_entreprise)
	{
		$http = new guzzleClient([
			'base_uri' => 'https://api.tassodelivery.com/',
			'headers' =>
				[
					'Accept' => 'application/json',
					'Content-Type' => 'application/json'
				]
		]);

		try {
				$http->post('Course/Simulate',
				[
					'form_params' =>
						[
							"public_key" => config('services.ortoo.key'),
							"Steps" =>
								[
									[
										"address" => $addresse_entreprise,
									],
									[
										"address" => $addresse_client
									]
								]
						]
				]);
				return [
					"result" => true,
					"message" => 'Verification ok.'
				];
			} catch (ClientException $e) {
				$response = $e->getResponse();
				$responseBodyAsString = $response->getBody()->getContents();
				$error_obj = json_decode($responseBodyAsString);
				return [
						"result" => false,
						"message" => $error_obj->Error
					];
			}
	}

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation du statut Ortoo d'une commande entreprise
	 * @param $facture
	 * @return array
	 */
	private function getEntrepriseCommandStatut($facture)
	{
		$commandes = $facture['bon_commande'];
		$livraisons = [];

		if ($facture->entreprise_id != 0) {
			$livraisons[$commandes['num_commande']] = [
				'num_cmd' => $commandes['num_commande'],
				'vendor_name' => $commandes['entreprise_infos']['nom_enseigne'],
				'refcourse' => $commandes['ref_ortoo'],
				'course_details' => $this->getCourseDetails($commandes['ref_ortoo']),
			];
		}
		return $livraisons;
	}
}
