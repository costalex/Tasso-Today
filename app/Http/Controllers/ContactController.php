<?php

namespace App\Http\Controllers;

use App\Contact;
use Carbon\Carbon;

class ContactController extends Controller
{
	/**
	 * CREATE
	 */

	/**
	 * Creation d'un contact et retour de l'id du nouveau contact crée
	 * @param $datas
	 * @return mixed
	 */
	public function createContact($datas)
	{
		return Contact::create([
			'nom' => isset($datas['nom']) && !empty($datas['nom']) ? $datas['nom'] : "",
			'prenom' => isset($datas['prenom']) && !empty($datas['prenom']) ? $datas['prenom'] : "",
			'telephone' => isset($datas['telephone']) && !empty($datas['telephone']) ? $datas['telephone'] : "",

			'addresse' => isset($datas['addresse']) && !empty($datas['addresse']) ? $datas['addresse'] : "",
			'code_postal' => isset($datas['code_postal']) && !empty($datas['code_postal']) ? $datas['code_postal'] : "",
			'commune' => isset($datas['commune']) && !empty($datas['commune']) ? $datas['commune'] : "",
			'email' => isset($datas['email']) && !empty($datas['email']) ? $datas['email'] : "",
			'ville' => isset($datas['ville']) && !empty($datas['ville']) ? $datas['ville'] : "",

			'addresse_fact' => isset($datas['addresse_fact']) && !empty($datas['addresse_fact']) ? $datas['addresse_fact'] : "",
			'code_postal_fact' => isset($datas['code_postal_fact']) && !empty($datas['code_postal_fact']) ? $datas['code_postal_fact'] : "",
			'commune_fact' => isset($datas['commune_fact']) && !empty($datas['commune_fact']) ? $datas['commune_fact'] : "",
			'email_fact' => isset($datas['email_fact']) && !empty($datas['email_fact']) ? $datas['email_fact'] : "",
			'ville_fact' => isset($datas['ville_fact']) && !empty($datas['ville_fact']) ? $datas['ville_fact'] : "",
		])->id;
	}

	/**
	 * UPDATE
	 */

	/**
	 * Mise a jour des informations d'un contact en fonction de son id et des paramettres settés
	 * @param $datas
	 * @return bool
	 */
	public function updateContact($datas)
	{
		Contact::where('id', $datas['id'])
			->when(isset($datas['nom']) && !empty($datas['nom']), function ($query) use ($datas) { $query->update(['nom' => $datas['nom']]); })
			->when(isset($datas['prenom']) && !empty($datas['prenom']), function ($query) use ($datas) { $query->update(['prenom' => $datas['prenom']]); })
			->when(isset($datas['telephone']) && !empty($datas['telephone']), function ($query) use ($datas) { $query->update(['telephone' => $datas['telephone']]); })

			->when(isset($datas['addresse']) && !empty($datas['addresse']), function ($query) use ($datas) { $query->update(['addresse' => $datas['addresse']]); })
			->when(isset($datas['code_postal']) && !empty($datas['code_postal']), function ($query) use ($datas) { $query->update(['code_postal' => $datas['code_postal']]); })
			->when(isset($datas['commune']) && !empty($datas['commune']), function ($query) use ($datas) { $query->update(['commune' => $datas['commune']]); })
			->when(isset($datas['email']) && !empty($datas['email']), function ($query) use ($datas) { $query->update(['email' => $datas['email']]); })
			->when(isset($datas['ville']) && !empty($datas['ville']), function ($query) use ($datas) { $query->update(['ville' => $datas['ville']]); })

			->when(isset($datas['addresse_fact']) && !empty($datas['addresse_fact']), function ($query) use ($datas) { $query->update(['addresse_fact' => $datas['addresse_fact']]); })
			->when(isset($datas['code_postal_fact']) && !empty($datas['code_postal_fact']), function ($query) use ($datas) { $query->update(['code_postal_fact' => $datas['code_postal_fact']]); })
			->when(isset($datas['commune_fact']) && !empty($datas['commune_fact']), function ($query) use ($datas) { $query->update(['commune_fact' => $datas['commune_fact']]); })
			->when(isset($datas['email_fact']) && !empty($datas['email_fact']), function ($query) use ($datas) { $query->update(['email_fact' => $datas['email_fact']]); })
			->when(isset($datas['ville_fact']) && !empty($datas['ville_fact']), function ($query) use ($datas) { $query->update(['ville_fact' => $datas['ville_fact']]); });
		return true;
	}

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation d'une liste de contact
	 * @param $contacts_list
	 * @return mixed
	 */
	public function getContactsIn($contacts_list)
	{
		return Contact::whereIn('id', $contacts_list)->get();
	}

	/**
	 * DELETE
	 */

	/**
	 * Permet la surppresiont d'un contact par son id
	 * @param $contact_id
	 */
	public function deleteContact($contact_id)
	{
		Contact::where(['id' => $contact_id])->update(['deleted_at' => Carbon::now()]);
	}
}
