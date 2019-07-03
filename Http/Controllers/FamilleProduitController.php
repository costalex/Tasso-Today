<?php

namespace App\Http\Controllers;

use App\FamilleProduit;

use function sizeof;

class FamilleProduitController extends Controller
{
	/**
	 * GETTERS
	 */

    /**
     * Affiche les familles de produit disponible
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $famille_produit = FamilleProduit::all();

    	$famille_produit[sizeof($famille_produit)] =
	    [
	    	'id' => 0,
	    	'nom' => 'Familles'
	    ];
        return $famille_produit;
    }

	/**
	 * Recuperation des informations d'une famille en fonction de son id
	 * @param $famille_id
	 * @return mixed
	 */
	public function getFamille($famille_id)
	{
		if (!empty($famille = FamilleProduit::where(['id' => $famille_id])->first()))
			return $famille;
		else
			abort(400, "Famille inconnue.");
	}
}
