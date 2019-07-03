<?php

namespace App\Http\Controllers;

use function abort;
use App\TypeEntreprise;

class TypeEntrepriseController extends Controller
{
	/**
	 * GETTERS
	 */

	/**
	 * Recuperation des types d'entreprise
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function typeEntrepriseList()
	{
		return TypeEntreprise::all('abreviation', 'nom');
	}

	/**
	 * Recuperation de l'id d'un type d'entreprise en fonction de son nom
	 * @param $type_entreprise_nom
	 * @return mixed
	 */
	public function getTypeEntrepriseId($type_entreprise_nom)
	{
		if (empty($type_entreprise = TypeEntreprise::where(['abreviation' => $type_entreprise_nom])->first()))
			abort(400, "Type d'entreprise incoonnu.");
		return $type_entreprise->id;
	}
}
