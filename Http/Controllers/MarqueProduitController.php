<?php

namespace App\Http\Controllers;

use function abort;
use function app;
use App\MarqueProduit;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use function mb_strtoupper;

class MarqueProduitController extends Controller
{
	/**
	 * ADD
	 */

	/**
	 * Ajout d'une nouvelle marque si elle n'existe pas.
	 * @param Request $request
	 */
	public function addMarque(Request $request)
	{
		$errors_messages = [
			'required' => 'The :attribute field is required.',
			'string' => 'The :attribute must already have letters.',
			'max' => 'The :attribute input is too long.',
			'unique' => 'The :attribute already exist.'
		];
		Validator::make($request->all(), [
			'nom' => 'required|string|max:50'
		],$errors_messages)->validate();

		if (empty(MarqueProduit::where('nom', $request->nom)->first()))
			app('App\Http\Controllers\FileManager')->migrationMarquesSave($request->nom);

		return MarqueProduit::firstOrCreate(['nom' => mb_strtoupper($request->nom, 'UTF-8')]);
	}

	/**
	 * GETTERS
	 */

	/**
     * Affiche les marques de produit disponible
     *
     * @return \Illuminate\Http\Response
     */
    public function autoCompletion($nom)
    {
        if (!empty($nom))
        {
	        return MarqueProduit::where('nom', 'LIKE', $nom . '%')
		        ->orderBy('nom', 'asc')
		        ->limit(5)
		        ->get();
        }
	    abort(400, 'Marque ne peut pas etre vide.');
    }

	/**
	 * Recuperation de l'id d'une marque en fonction de son nom
	 * @param $nom
	 * @return mixed
	 */
    public function getMarqueId($nom)
    {
    	if (!empty($marque = MarqueProduit::where('nom', $nom)->first()))
    		return $marque->id;
    	else
    		abort(400, "Marque inconnue.");
    }

	/**
 	 * Recuperation des informations d'une marque en fonction de son id
	 * @param $marque_id
	 * @return mixed
	 */
	public function getMarque($marque_id)
	{
		if (!empty($marque = MarqueProduit::where('id', $marque_id)->first()))
			return $marque;
		else
			abort(400, "Marque inconnue.");
	}
}
