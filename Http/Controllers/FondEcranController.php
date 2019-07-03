<?php

namespace App\Http\Controllers;

use App\FondEcran;


class FondEcranController extends Controller
{
	/**
	 * Permet de recuperer l'id du fond ecran dont le nom est passé en paramettre
	 * @param $label_nom
	 * @return mixed
	 */
    public function getFondEcranId($label_nom)
    {
    	return (FondEcran::where(['label' => $label_nom])->first())->id;
    }
}
