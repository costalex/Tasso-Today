<?php

namespace App\Http\Controllers;

use function app;
use App\Demande;
use function array_push;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
	/**
	 * GETTERS
	 */

    /**
     * Affiche les demande en fonction des filtres
     *
     * @param  \App\Demande  $demande
     * @return \Illuminate\Http\Response
     */
    public function demandeList(Request $request)
    {
		$demandes = [];
		//refaire un getter pour les entreprises nouvellement crées
	    $entreprise_list = app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseList($request);

	    foreach ($entreprise_list as $entreprise)
		{
			$demandes = Demande::where(['entreprise_id' => $entreprise['id']])->get();
			foreach ($demandes as $demande)
			{
				array_push($demandes, [
					'id' => $demande["id"],
					'entreprise_id' => $demande["entreprise_id"],
					'ville_id' => $demande["ville_id"],
					'type_activite_id' => $demande["type_activite_id"],
					'type' => $demande["type"],
					'status' => $demande["status"],
					'details' => $demande["details"]
				]);
			}
		}
		return $demandes;
    }
}
