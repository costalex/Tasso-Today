<?php

namespace App\Http\Controllers;

use App\CategorieProduit;
use function array_push;


class CategorieProduitController extends Controller
{
	/**
	 * GETTERS
	 */

    /**
     * Affiche les catogories en fonction de leurs dependances avec ajout d'un champ par defaut
     *
     * @param  int  $famille_id
     * @return \Illuminate\Http\Response
     */
	public function show($famille_id)
	{
		$filter_categories_produit = $this->getCategoriesFromFamilleID($famille_id);

		$filter_categories_produit[sizeof($filter_categories_produit)] =
		    [
			    'id' => 0,
			    'nom' => 'Categories'
		    ];
	    return $filter_categories_produit;
    }

	/**
	 * Filtre des categories en fonction de leurs dependance a l'id famille selectionnée
	 * @param $famille_id
	 * @return array
	 */
    private function getCategoriesFromFamilleID($famille_id)
    {
	    $all_categories_produit = CategorieProduit::all('id', 'dependances_familles_produits');
	    $filter_categories_produit = [];

		foreach ($all_categories_produit as $tab)
			foreach ($tab['dependances_familles_produits'] as $index)
				if ($index == $famille_id)
				{
					array_push($filter_categories_produit, CategorieProduit::where('id', $tab['id'])->first());
					break;
				}

	    return $filter_categories_produit;
    }

	/**
	 * Recuperation des informations d'une categorie en fonction de son id
	 * @param $cat_id
	 * @return mixed
	 */
	public function getCategorie($cat_id)
	{
		if (empty($categorie = CategorieProduit::where(['id' => $cat_id])->first()))
			abort(400, "Categorie inconnue.");
		else
			return $categorie;
	}
}
