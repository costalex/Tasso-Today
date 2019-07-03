<?php

namespace App\Http\Controllers;

use App\Ville;

class VilleController extends Controller
{
	/**
	 * GETTERS
	 */

	/**
	 * Recuperation de la lite des villes
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
    public function villeList()
    {
    	$villes = Ville::all('id', 'nom');
	    $villes[sizeof($villes)] =
		    [
			    'id' => 0,
			    'nom' => 'Villes'
		    ];
    	return $villes;
    }

	/**
	 * Recuperation de l'id de la ville par son nom
	 * @param $ville_nom
	 * @return mixed
	 */
    public function getVilleId($ville_nom)
    {
    	return (Ville::where('nom', $ville_nom)->first())->id;
    }
}
