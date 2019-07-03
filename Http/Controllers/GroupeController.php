<?php

namespace App\Http\Controllers;

use App\Groupe;


class GroupeController extends Controller
{
	/**
	 * GETTERS
	 */

    /**
     * Affiche la liste de groupe
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Groupe::all('label');
    }
}
