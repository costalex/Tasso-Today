<?php

namespace App\Http\Controllers;

use App\Abonnement;

class AbonnementController extends Controller
{
	/**
	 * GETTERS
	 */

	/**
	 * Retourne la liste de tous les abonnements
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAbonnementList()
	{
		return Abonnement::all();
	}

	/**
	 * Recuperation de l'id d'un abonnement en fonction de son nom
	 * @param $abonnement_nom
	 * @return mixed
	 */
    public function getAbonnementId($abonnement_nom)
    {
	    if (empty($abonnement = Abonnement::where(['nom' => $abonnement_nom])->first()))
		    abort(400, "Abonnement incoonnu.");
	    return $abonnement->id;
    }
}
