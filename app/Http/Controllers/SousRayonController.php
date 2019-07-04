<?php

namespace App\Http\Controllers;

use App\SousRayon;
use Illuminate\Http\Request;

class SousRayonController extends Controller
{
	/**
	 * CREATE
	 */

	/**
  	 * Creation d'un nouveau sous-rayon en fonction des limitations de l'abonnement de l'entreprise
	 * @param Request $request
	 */
	public function createSousRayon(Request $request)
	{
//		$infos_check_sous_rayon = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Sous_Rayons');

		//Mise en commentaire de la limitation de creation par rapport a un abonnement
//		if (SousRayon::where('entreprise_id', $infos_check_sous_rayon["entreprise_id"])->count() < $infos_check_sous_rayon['abo_nb_max_sous_rayons'])
//		{
				return ["id" => SousRayon::create([
					'nom' => $request->nom,
					'entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),
					'rayon_id' => $request->rayon_id
				])["id"]];
//		}
		abort(400, "Limit de Sous-Rayons atteint.");
	}

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation de la liste des sous-rayon en fonction de l'entreprise_id et du rayon_id a laquel il appartient
	 * @param $rayon_id
	 * @return mixed
	 */
	public function getSousRayonList($rayon_id)
	{
//		$infos_check_sous_rayon = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Sous_Rayons');

		if (!empty($sous_rayon = SousRayon::where(
			[
				'entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),
				'rayon_id' => $rayon_id
			])
			->with('rayon')
			->get()))
			return $sous_rayon;
		else
			abort(400, "Aucun(s) sous-rayon(s) trouve");
	}

	/**
	 * UPDATE
	 */

	/**
  	 * Modifications des information(s) liées aux sous-rayons
	 * @param Request $request
	 */
	public function updateSousRayonInformations(Request $request)
	{
//		$infos_check_sous_rayon = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Sous_Rayons');

		if (!empty($sous_rayon = SousRayon::where([
			'entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),
			'id' => $request->sous_rayon_id
		])->first()))
		{
			$sous_rayon->nom = !empty($request->new_nom) ? $request->new_nom : $sous_rayon->nom;
			$sous_rayon->save();
			abort(200, "Sous-rayon modifier.");
		}
		else
			abort(400, "Sous-rayon inconnu.");
	}

	/**
	 * DELETE
	 */

	/**
	 * Suppression d'un sous-rayon en se basant sur son nom (envoye par le client)

	 * @param $sous_rayon_nom
	 */
	public function deleteSousRayon(Request $request)
	{
		$internal_call = empty($request->internal_call) ? false : $request->internal_call;
//		$infos_check_sous_rayon = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Sous_Rayons');

		if (!empty($sous_rayons = SousRayon::where(['entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),])
			->when($request->sous_rayon_id, function ($query) use ($request)
			{
				return $query->where(['id' => $request->sous_rayon_id]);
			})
			->when($request->rayon_id, function ($query) use ($request)
			{
				return $query->where(['rayon_id' => $request->rayon_id]);
			})
			->get()))
		{
			foreach ($sous_rayons as $sous_rayon)
			{
				app('App\Http\Controllers\EtagereController')->deleteEtagere(new Request([
					"sous_rayon_id" => $sous_rayon['id'],
					"internal_call" => true
				]));
				$sous_rayon->delete();
			}

			if ($internal_call)
				return true;
			else
				abort(200, "Sous-rayon et etagere dependantes supprime.");
		}
		else
		{
			if ($internal_call)
				return false;
			else
				abort(400, "Sous-rayon inconnu.");
		}
	}
}
