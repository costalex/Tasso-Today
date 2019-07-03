<?php

namespace App\Http\Controllers;

use function abort;
use App\Couleur;

class CouleurController extends Controller
{
	/**
	 * GETTERS
	 */

	/**
	 * Permet de recuperer la liste des couleurs disponible dans la creation d'un stock
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
    public function getCouleurList()
    {
    	return Couleur::all();
    }

	/**
	 * Recuperation d'une couleur par son nom
	 * @param $nom_couleur
	 * @return mixed
	 */
    public function getCouleur($nom_couleur)
    {
    	if (!empty($couleur = Couleur::where(['nom' => $nom_couleur])->first()))
		    return $couleur;
    	else
    		abort(400, "Couleur non trouvee");
    }
}
