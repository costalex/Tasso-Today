<?php

namespace App\Http\Controllers;

use App\TypeActivite;

class TypeActiviteController extends Controller
{
	/**
	 * Recuperation de la liste de toutes les activités d'entreprise
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
    public function typeActiviteList()
    {
	    $types_activite = TypeActivite::all('id', 'nom');
	    $types_activite[sizeof($types_activite)] =
		    [
			    'id' => 0,
			    'nom' => "Secteurs d'activites"
		    ];
	    return $types_activite;
    }

	/**
	 * Recuperation de l'id correspondant a un nom d'activite d'entreprise
	 * @param $type_activite_nom
	 * @return mixed
	 */
    public function getTypeActiviteId($type_activite_nom)
    {
	    if (empty($type_activite = TypeActivite::where(['nom' => $type_activite_nom])->first()))
		    abort(400, "Type d'activite incoonnu.");
	    return $type_activite->id;
    }
}
