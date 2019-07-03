<?php

namespace App\Http\Controllers;

use function abort;
use App\Rayon;
use Illuminate\Http\Request;

class RayonController extends Controller
{
	/**
	 * CREATE
	 */

	/**
 	 * Creation d'un nouveau rayon en fonction des limitations de l'abonnement de l'entreprise
	 * @param Request $request
	 */
	public function createRayon(Request $request)
	{
//		$infos_check_rayon = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Rayons');

		//Mise en commentaire de la limitation de creation par rapport a un abonnement
//		if (Rayon::where('entreprise_id', $infos_check_rayon["entreprise_id"])->count() < $infos_check_rayon['abo_nb_max_rayons'])
//		{
			return ["id" => Rayon::create([
					'nom' => $request->nom,
					'entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId()
				])["id"]];
//		}
		abort(400, "Limit de Rayons atteint.");
	}

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation de la liste des rayons crees par l'entreprise
	 * @return mixed
	 */
	public function getRayonList()
	{
//		$infos_check_rayon = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Rayons');

		if (!empty($rayons = Rayon::where('entreprise_id', app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId())->get()))
			return $rayons;
		else
			abort(400, "Aucun(s) rayon(s) trouve");
	}

	/**
	 * UPDATE
	 */

	/**
	 * Modifications des information(s) liées aux rayons
	 * @param Request $request
	 */
	public function updateRayonInformations(Request $request)
	{
//		$infos_check_rayon = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Rayons');

		if (!empty($rayon = Rayon::where([
			'entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),
			'id' => $request->rayon_id
		])->first()))
		{
			$rayon->nom = !empty($request->new_nom) ? $request->new_nom : $rayon->nom;
			$rayon->save();
			abort(200, "Rayon modifier.");
		}
		else
			abort(400, "Rayon n'a pas pus etre modifier.");
	}

	/**
	 * DELETE
	 */

	/**
	 * Suppression d'un rayon en se basant sur son nom (envoye par le client)
	 * @param $rayon_nom
	 */
	public function deleteRayon($rayon_id)
	{
//		$infos_check_rayon = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Rayons');

		if (!empty($rayon = Rayon::where([
			'entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),
			'id' => $rayon_id
		])->first()))
		{
			app('App\Http\Controllers\SousRayonController')->deleteSousRayon(new Request([
				"rayon_id" => $rayon_id,
				"internal_call" => true
			]));
			$rayon->delete();
			abort(200, "Rayon, Sous-rayon et etagere dependantes supprime.");
		}
		else
			abort(400, "Rayon non trouve.");
	}


}
