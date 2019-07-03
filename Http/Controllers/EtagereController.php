<?php

namespace App\Http\Controllers;

use function abort;
use App\Etagere;
use Illuminate\Http\Request;
use function json_encode;
use function var_dump;


class EtagereController extends Controller
{
	/**
	 * CREATE
	 */

	/**
	 * Creation d'une nouvelle etagere en fonction des limitations de l'abonnement de l'entreprise
	 * @param Request $request
	 */
	public function createEtagere(Request $request)
	{
//		$infos_check_etageres = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Etageres');

		//Mise en commentaire de la limitation de creation par rapport a un abonnement
//		if (Etagere::where(['entreprise_id' => $infos_check_etageres["entreprise_id"]])->count() < $infos_check_etageres['abo_nb_max_etageres'])
//		{
			$new_etagere = Etagere::create([
				'nom' => $request->nom,
				'rayon_id' => $request->rayon_id,
				'sous_rayon_id' => $request->sous_rayon_id,
				'entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),
				"list_produits" => "{}"
			]);
			$new_etagere->list_produits = [];
			$new_etagere->save();
			return ["id" => $new_etagere["id"]];
//		}
		abort(400, "Limit d'etageres atteint.");
	}

	/**
	 * Ajout d'un produit dans une etagere cible par nom rayon_id sous_rayon_id et entreprise_id
	 * @param Request $request
	 * @return mixed
	 */
	public function addProduitToEtagere(Request $request)
	{
		$entreprise_id = app('App\Http\Controllers\EntrepriseInformationsController')
			->getEntrepriseId();

		if (empty($etagere = Etagere::where([
			'id' => $request->etagere_id,
			'rayon_id' => $request->rayon_id,
			'sous_rayon_id' => $request->sous_rayon_id,
			'entreprise_id' => $entreprise_id
		])->first()))
			abort(400, "Etagere inconnue.");

		if ($this->getNbProduitsInProduitList($etagere->list_produits) >= 12)
			abort(400, "Nombre maximum d'ajout de produits dans l'etagere atteint.");

		$etagere->list_produits = $request->list_produits;
		$etagere->save();

		$entreprise_produits_list = app('App\Http\Controllers\EntrepriseInformationsController')
			->getEntrepriseProduitsList();

		return $this->getProduitsDetailsToEtagereList($request->list_produits, $entreprise_produits_list);
	}

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation de la liste d'etageres de l'entreprise avec les rayon, sous-rayons, et liste des produits associés
	 * @return array
	 */
	public function getEtageresList()
	{
//		$infos_check_etageres = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Etageres');

		if (empty($etageres = Etagere::where(['entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId()])
			->with('rayon', 'sous_rayon', 'fondEcran')
			->get()))
			abort(400, "Aucune(s) etagere(s) trouve.");

		$formated_etageres = [];

		$shop_order = app('App\Http\Controllers\EntrepriseInformationsController')
			->getShopOrder();
		$entreprise_produits_list = app('App\Http\Controllers\EntrepriseInformationsController')
			->getEntrepriseProduitsList();

		if (!empty($shop_order))
			$formated_etageres = $this->organiseEtageresByShopOrder($etageres, $shop_order, $entreprise_produits_list);
		else
			return [];

		//Optimisation possible en re-travaillant le coté client sur un re-traitement inutile des informations
		return $formated_etageres;
	}

	/**
	 * Acces au shopOrder d'une entreprise par un client non-connecter afin d'afficher le store d'une entreprise
	 * @param $ville
	 * @param $entreprise_nom
	 * @return array
	 */
	public function getShopFor($ville, $entreprise_nom)
	{
		if(!($entreprise = app('App\Http\Controllers\EntrepriseInformationsController')
			->getEntrepriseInfosByName($ville,$entreprise_nom)))
			abort(400, "Entreprise inconnue.");

		if ($entreprise->status != 'OUVERT')
			return [];

		if (empty($etageres = Etagere::where(["entreprise_id" => $entreprise['id']])
			->with('rayon', 'sous_rayon', 'fondEcran')
			->get()))
			abort(400, "Aucune(s) etagere(s) trouve.");

		$formated_etageres = [];

		$shop_order = $entreprise['shop_order'];
		$entreprise_produits_list = $entreprise["liste_produits"];

		if (empty($shop_order))
		{
			foreach ($etageres as $etagere)
			{
				if (empty($formated_etageres[$etagere["rayon"]["nom"]]))
				{
					$formated_etageres[$etagere["rayon"]["nom"]] = [
						"rayon_nom" => $etagere["rayon"]["nom"],
						"rayon_id" => $etagere["rayon"]["id"]
					];
				}

				if (empty($formated_etageres[$etagere["rayon"]["nom"]][$etagere["sous_rayon"]["nom"]]))
				{
					$formated_etageres[$etagere["rayon"]["nom"]][$etagere["sous_rayon"]["nom"]] = [
						"sous_rayon_nom" => $etagere["sous_rayon"]["nom"],
						"sous_rayon_id" => $etagere["sous_rayon"]["id"]
					];
				}

				if (empty($formated_etageres[$etagere["rayon"]["nom"]][$etagere["sous_rayon"]["nom"]][$etagere["nom"]]))
				{
					$formated_etageres[$etagere["rayon"]["nom"]][$etagere["sous_rayon"]["nom"]][$etagere["nom"]] = [
						"etagere_id" => $etagere["id"],
						"etagere_nom" => $etagere["nom"],
						"type" => $etagere["type"],
						"list_produits" => $this->getProduitsDetailsToEtagereList($etagere["list_produits"],$entreprise_produits_list),
						"fondEcran" => $etagere["fondEcran"]
					];
				}
			}
		}
		else
			$formated_etageres = $this->organiseEtageresByShopOrder($etageres, $shop_order,$entreprise_produits_list);

		//Optimisation possible en re-travaillant le coté client sur un re-traitement inutile des informations
		return $formated_etageres;
	}

	/**
	 * Ajout des produits de l'entreprise dans l'etagere dans l'ordre setter par le client lors de l'application du fond ecran
	 */
	public function getProduitsDetailsToEtagereList($etagere_produit_list, $entreprise_produits_list)
	{
		if (!empty($etagere_produit_list))
		{
			foreach ($etagere_produit_list as $i => $etagere_produit)
			{
				if (isset($etagere_produit["id_produit"]))
				{
					foreach ($entreprise_produits_list as $produit)
					{
						if ($produit["id"] == $etagere_produit["id_produit"])
						{
							unset($etagere_produit_list[$i]["id_produit"]);
							$etagere_produit_list[$i]["id_produit"] = $produit;
						}
					}
				}
			}
		}
		else
			return [];
		return $etagere_produit_list;
	}

	/**
	 * Recuperation du nombre de produits dans l'etagere en cours de modification
	 * @param $produits_list
	 * @return int
	 */
	private function getNbProduitsInProduitList($produits_list)
	{
		$nb_produits = 0;

		foreach ($produits_list as $produit_list)
			$nb_produits += isset($produit_list["id_produit"]) ? 1 : 0;
		return $nb_produits;
	}

	/**
	 * Recuperation des informations rayons, sous-rayons et produits de l'etagere
	 * @param $entreprise_id
	 * @return mixed
	 */
	public function getProduitsEtagereListFor($entreprise_id)
	{
		return Etagere::where(['entreprise_id' => $entreprise_id])
			->with('rayon', 'sous_rayon', 'fondEcran')
			->get();
	}

	/**
	 * TOOLS
	 */

	/**
	 * Deplacement des rayons, sous-rayon, etageres et produits en fonction de l'ordre des rayons associés
	 * @param $rayons
	 * @return array
	 */
	public function organiseEtageresByShopOrder($etageres, $shop_order, $entreprise_produits_list)
	{
		foreach ($shop_order as $item_rayon => $rayon)
		{
			foreach ($rayon as  $item_sous_rayon => $sous_rayon)
			{
				if ($item_sous_rayon != "rayon_nom" && $item_sous_rayon != "rayon_id")
					foreach ($sous_rayon as $item_etagere => $etagere)
					{
						if ($item_etagere != "sous_rayon_nom" && $item_etagere != "sous_rayon_id")
						{
							foreach ($etageres as $found_etagere)
							{
								if ($etagere["etagere_id"] == $found_etagere["id"])
								{
									$shop_order[$item_rayon][$item_sous_rayon][$item_etagere] = [
										"etagere_id" => $found_etagere["id"],
										"etagere_nom" => $found_etagere["nom"],
										"type" => $found_etagere["type"],
										"list_produits" => $this->getProduitsDetailsToEtagereList($found_etagere["list_produits"], $entreprise_produits_list),
										"fondEcran" => $found_etagere["fondEcran"]
									];
									break;
								}
							}
						}
					}
			}
		}

		return $shop_order;
	}

	/**
	 * UPDATE
	 */

	/**
	 * Modifications des informations liées a l'etagere
	 * @param Request $request
	 */
	public function updateEtagere(Request $request)
	{
//		$infos_check_etageres = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Etageres');

		if (empty($etageres = Etagere::where([
			"entreprise_id" => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),
			"id" => $request->etagere_id,
			'rayon_id' => $request->rayon_id,
			'sous_rayon_id' => $request->sous_rayon_id
		])->first()))
			abort(400, "Aucune(s) etagere(s) trouve.");
		$etageres->nom = !empty($request->new_nom) ? $request->new_nom : $etageres->new_nom;
		$etageres->list_produits = !empty($request->list_produits) ? $request->list_produits : $etageres->list_produits;
		$etageres->save();
		abort(200, "Etagere mise a jour.");
	}

	/**
	 * DELETE
	 */

	/**
	 * Suppression d'un produit dans une etagere
	 * @param Request $request
	 * @return bool
	 */
	public function deleteEtagere(Request $request)
	{
		$internal_call = empty($request->internal_call) ? false : $request->internal_call;
//		$infos_check_etageres = app('App\Http\Controllers\EntrepriseInformationsController')
//			->getAbonnementLimitationFor('Etageres');

		if (!empty($etageres = Etagere::where([
			'entreprise_id' => app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseId(),
		])->when($request->etagere_id, function ($query) use ($request)
		{
			return $query->where(['id' => $request->etagere_id]);
		})
			->when($request->rayon_id, function ($query) use ($request)
			{
				return $query->where(['rayon_id' => $request->rayon_id]);
			})
			->when($request->sous_rayon_id, function ($query) use ($request)
			{
				return $query->where(['sous_rayon_id' => $request->sous_rayon_id]);
			})->get()))
		{
			foreach ($etageres as $etagere)
				$etagere->delete();

			if ($internal_call)
				return true;
			else
				abort(200, "Etagere supprimee.");
		}
		else
		{
			if ($internal_call)
				return false;
			else
				abort(400, "Etagere inconnue.");
		}
	}

	/**
	 * Suppresion d'un produit dans l'etagere ciblée
	 * @param Request $request
	 * @return mixed
	 */
	public function deleteProduitToEtagere(Request $request)
	{
		$entreprise_id = app('App\Http\Controllers\EntrepriseInformationsController')
			->getEntrepriseId();

		if (empty($etagere = Etagere::where([
			'id' => $request->etagere_id,
			'rayon_id' => $request->rayon_id,
			'sous_rayon_id' => $request->sous_rayon_id,
			'entreprise_id' => $entreprise_id
		])->first()))
			abort(400, "Etagere inconnue.");
		$entreprise_produits_list = app('App\Http\Controllers\EntrepriseInformationsController')
			->getEntrepriseProduitsList();

		$etagere_produits = $etagere->list_produits;

		foreach ($etagere_produits as $i => $produit)
		{
			if ($produit['id_position'] == $request->id_position)
			{
				unset($etagere_produits[$i]["id_produit"]);
			}
		}
		$etagere->list_produits = $etagere_produits;
		$etagere->save();

		return $this->getProduitsDetailsToEtagereList($etagere->list_produits, $entreprise_produits_list);
	}

	/**
	 * Lors d'une suppression par un admin d'un produit appartenant a une entreprise on automatise la suppression dans
	 * toutes les etageres afin que ce produit ne reste pas dans l'entreprise
	 * @param Request $request
	 * @return mixed
	 */
	public function deleteProduitInAllEtageres($entreprise_id, $produit_id)
	{
		$produits_etagere = Etagere::select('id', 'list_produits')
			->where(['entreprise_id' => $entreprise_id])
			->get();

		foreach ($produits_etagere as $i => $etageres)
		{
			$tmp_produits = $etageres['list_produits'];
			$tmp_etagere_id = $etageres['id'];
			foreach ($etageres['list_produits'] as $j => $etagere)
			{
				if (isset($etagere["id_produit"]) && !empty($etagere["id_produit"]) && $etagere["id_produit"] == $produit_id)
				{
					unset($tmp_produits[$j]["id_produit"]);
					Etagere::where([
						'id' => $tmp_etagere_id,
						'entreprise_id' => $entreprise_id
					])->update(['list_produits' => json_encode($tmp_produits)]);
				}
			}
		}

	}
}
