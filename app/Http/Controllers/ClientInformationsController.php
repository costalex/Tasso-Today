<?php

namespace App\Http\Controllers;

use function abort;
use function app;
use App\Client;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ClientInformationsController extends Controller
{
	/**
	 * GETTERS
	 */

	/**
	 * Recuperation des informations d'un client(acheteur)
	 * @return mixed
	 */
	public function showClientInformations()
	{
		if (!empty($client = Client::where('user_id', Auth::user()->id)->with('user', 'contacts_facturation')->first()))
		{
			$client->list_groupe_id = app('App\Http\Controllers\UserInformationsController')->getGroupesName($client->list_groupe_id);
			$client->user->user_type_id = 'Client';

			$client->addresses_livraison = app('App\Http\Controllers\ContactController')->getContactsIn($client->addresses_livraison);

			return Response::json($client);
		}
		return Response::json([
			'user' =>
				['user_type_id' => 'Guest']
		]);
	}

	/**
	 * Permet la recuperation d'informations detaillées sur un client en particulié (acces admin et dev)
	 * @param $id
	 * @return mixed
	 */
	public function showDetailsClientInformations($id)
	{
		if (!empty($client = Client::where(['id' => $id])->with('user')->first()))
		{
			$client->list_groupe_id = app('App\Http\Controllers\UserInformationsController')->getGroupesName($client->list_groupe_id);
			$client->user->user_type_id = 'Client';
			return Response::json($client);
		}
		return Response::json([
			'user' =>
				['user_type_id' => 'Guest']
		]);
	}

	/**
	 * Recuperation des informations de paiement d'un client
	 * @param $client_id
	 * @return mixed
	 */
	public function getClientPaimentInformations($client_id)
	{
		return Response::json(Client::select('id', 'user_id')->where(['id' => $client_id])->with(['userPaiementInfos' => function($query)
		{
			$query->with('userType');
		}])->first());
	}

	/**
	 * Récuperation de l'id client a l'aide du user id de connection
	 * @param $client_id
	 * @return mixed
	 */
	public function getUserIdFromClientId($client_id)
	{
		if (!empty($user_id = Client::select('user_id', 'id')->where('id',$client_id)->first()->user_id))
			return $user_id;
		else
			abort(400, "Utilisateur non trouvé");
	}

	/**
	 * UPDATE
	 */

	/**
	 * Mise a jour des informations client(acheteur)
	 * @param Request $request
	 */
	public function updateClient(Request $request)
	{
		/**modification specifique aux Acheteurs(Clients)*/
		if (!empty($client = Client::where('user_id', Auth::user()->id)->first()))
		{
			$client->list_groupe_id = !empty($request->code_groupe) ? app('App\Http\Controllers\UserInformationsController')->addGroupe($client->list_groupe_id, $request->code_groupe) : $client->list_groupe_id;

			$client->nom = !empty($request->nom) ? $request->nom : $client->nom;
			$client->prenom = !empty($request->prenom) ? $request->prenom : $client->prenom;
			$client->telephone = !empty($request->telephone) ? $request->telephone : $client->telephone;

			app('App\Http\Controllers\ContactController')->updateContact(
				[
					'id' => $client->addresse_facturation,
					'nom' => $request->nom,
					'prenom' => $request->prenom,
					'telephone' => $request->telephone,
					'addresse_fact' => $request->addresse,
					'code_postal_fact' => $request->code_postal,
					'ville_fact' => $request->ville
				]);

			app('App\Http\Controllers\ContactController')->updateContact(
				[
					'id' => $client->addresses_livraison[0],
					'nom' => $request->nom,
					'prenom' => $request->prenom,
					'telephone' => $request->telephone,
					'addresse' => $request->addresse,
					'code_postal' => $request->code_postal,
					'ville' => $request->ville
				]);

			// Se servir des information pour re-acuatliser les coordonnée GPS en fonction de l'addresse de facturation et code postal
			//$client->Coordonnées_GPS = !empty($request->Coordonnées_GPS) ? $request->Coordonnées_GPS : $client->Coordonnées_GPS;
			$client->list_entreprise_favoris = !empty($request->list_entreprise_favoris) ? $request->list_entreprise_favoris : $client->list_entreprise_favoris;
			$client->paniers = !empty($request->paniers) ? $request->paniers : $client->paniers;
			$client->liste_paniers_historique = !empty($request->liste_paniers_historique) ? $request->liste_paniers_historique : $client->liste_paniers_historique;

			$client->save();
			abort(200, "Informations utilisateur mises à jour.");
		}
		abort(400, "Utilisateur non trouvé");
	}
}
