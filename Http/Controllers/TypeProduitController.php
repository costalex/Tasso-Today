<?php

namespace App\Http\Controllers;

use App\TypeProduit;
use Illuminate\Support\Facades\Response;

class TypeProduitController extends Controller
{
	/**
	 * GETTERS
	 */

	/**
	 * Affiche les types en fonction de leurs dependances avec ajout d'un champ par defaut

	 * @param $categorie_id
	 * @return mixed
	 */
    public function show($categorie_id)
    {
	    $type_produit = $this->getTypesFromCategorieID($categorie_id);

	    $type_produit[sizeof($type_produit)] =
		    [
			    'id' => 0,
			    'nom' => 'Types'
		    ];
	    return Response::json($type_produit);
    }

	/**
	 * Filtre des types en fonction de leurs dependance a l'id categorie selectionnée
	 * @param $categorie_id
	 * @return array
	 */
	private function getTypesFromCategorieID($categorie_id)
	{
		$all_types_produit = TypeProduit::all('id', 'dependances_categories_produits');
		$filter_types_produit = [];

		foreach ($all_types_produit as $tab)
			foreach ($tab['dependances_categories_produits'] as $index)
				if ($index == $categorie_id)
				{
					array_push($filter_types_produit, TypeProduit::where('id', $tab['id'])->first());
					break;
				}

		return $filter_types_produit;
	}

	/**
	 * Recuperation des informations d'un type en fonction de son id
	 * @param $type_id
	 * @return mixed
	 */
	public function getType($type_id)
	{
		if (!empty($type = TypeProduit::where(['id' => $type_id])->first()))
		return $type;
	else
		abort(400, "Type inconnue.");
	}
}
