<?php

namespace App\Http\Controllers;

use function abort;
use function app;
use App\Entreprise;
use App\Produit;
use App\User;
use function array_push;
use Carbon\Carbon;
use function config;
use function enchant_dict_check;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\Response;
use function json_encode;
use function sizeof;
use function strcmp;
use function strlen;
use function substr;
use function var_dump;

/**
 * Class EntrepriseInformationsController
 * @package App\Http\Controllers
 */
class EntrepriseInformationsController extends Controller
{
	/**
	 * ADD
	 */

	/**
	 * Ajout du volume dans une liste de stocks d'un produit
	 * @param $list_stocks
	 * @return mixed
	 */
	public function addVolumeToStocks($list_stocks)
	{
		foreach ($list_stocks as $i => $list_stock)
		{
			$stock_volume = $list_stock["hauteur"] * $list_stock["longueur"] * $list_stock["largeur"];
			if ($stock_volume <= 39000)
			{
				$list_stocks[$i]["volume"] = $stock_volume;
			}
		}
		return $list_stocks;
	}

	/**
	 * Ajout d'une copie d'un produits à une entreprise par un admin
	 * @param Request $request
	 */
	public function addProduitsEntreprise(Request $request)
	{
		$entreprise = Entreprise::where(['id' => $request->id_entreprise])->first();

		$list_entreprise_produits = $entreprise->liste_produits;

		if ($new_produit = app('App\Http\Controllers\ProduitController')->getGenProduitInfosById($request->id_produit))
		{
			if (empty($list_entreprise_produits) || !$this->produitEntrepriseAlreadyExist($list_entreprise_produits, $new_produit))
			{
				$tmp_data = $new_produit['marque']['nom'];
				unset($new_produit['marque']);
				$new_produit['marque'] = $tmp_data;

				$new_produit['stocks'] = [
					0 => [
						"id" => 0,
						"model" => "Piece",
						"couleur" => [
							'nom' => "Aucune",
							'code_hexa' => ""
						],
						"prix" => 0,
						"activer" => false,
						"longueur" => 0,
						"largeur" => 0,
						"hauteur" => 0,
						"volume" => 0,
						"poids" => 0,
						"stock" => 0,
						"afficher" => false
					]
				];

				$list_entreprise_produits[$new_produit["nom"]] = $new_produit;

				$entreprise->liste_produits = $list_entreprise_produits;
				$entreprise->save();
			}
			else
				abort(400, "Produit deja ajoute a l'entreprise.");
		}
		else
			abort(400, "Produit non trouvé.");
		return $list_entreprise_produits;
	}

	/**
	 * CHECKS
	 */

	/**
	 * Verification de la cohérence des valeurs entrées par l'utilisateur concernant les stocks
	 * @param $list_stocks
	 * @return bool
	 */
	public function checkStockValues($list_stocks)
	{
		foreach ($list_stocks as $i => $list_stock)
		{
			if (!isset($list_stock["prix"]) || !isset($list_stock["model"]) ||
				!isset($list_stock["poids"]) || !isset($list_stock["stock"]) ||
				!isset($list_stock["couleur"]) || !isset($list_stock["hauteur"]) ||
				!isset($list_stock["largeur"]) || !isset($list_stock["longueur"]))
				abort(400, "STOCK n°" . ($i+1) . ": Informations manquantes.");
			if ($list_stock["stock"] == 0 && $list_stock["activer"] == true)
				abort(400, "STOCK n°" . ($i+1) . ": Un stock ne peut etre active sans stock.");
			if ($list_stock["stock"] == 0 && $list_stock["afficher"] == true)
				abort(400, "STOCK n°" . ($i+1) . ": Un stock ne peut etre afficher sans stock.");
			if ($list_stock["activer"] == false && $list_stock["afficher"] == true)
				abort(400, "STOCK n°" . ($i+1) . ": Un stock ne peut etre afficher sans etre active.");
			if (($list_stock["hauteur"] * $list_stock["longueur"] * $list_stock["largeur"]) > 39000)
				abort(400, "STOCK n°" . ($i+1) . ": Le volume d'un stock ne peut pas depasser 39000cm3");
			if ($list_stock["poids"] > 13000)
				abort(400, "STOCK n°" . ($i+1) . ": Le poids d'un stock ne peut pas depasser 13000g");
		}
		return true;
	}

	/**
	 * Retourne la liste de produit en indiquant si un produit appartient a l entreprise courante. Espace ADMIN
	 * @param Request $request
	 * @return mixed
	 */
	public function checkProduitsList(Request $request)
	{
		$index_produits_filtre = $request->produits_filtre;

		//Parcour de la liste des produits de l'entreprise pour chaque produit comparé afin de passer a TRUE les correspondances
		if (!empty($entreprise = Entreprise::select('id', 'liste_produits')
			->where(['id' => $request->id_entreprise])
			->first()))
		{
			foreach ($index_produits_filtre as $i => $produit_filtre)
			{
				$index_produits_filtre[$i]['have'] = false;
				foreach ($entreprise->liste_produits as $entreprise_produit)
				{
					if ($index_produits_filtre[$i]["id"] == $entreprise_produit['id'])
					{
						$index_produits_filtre[$i]['have'] = true;
						break;
					}
				}
			}
		}
		return $index_produits_filtre;
	}

	/**
	 * Appel automatisé
	 * Modification du statut des entreprises 'OUVERT','FERME','FERME_J','ARRET' en fonction de leurs horraires respectifs
	 */
	public function switchEntreprisesHorraires()
	{
		//Ajouter un statut supplementaire pour un arret a la journée dans l'exeption
		$entreprises = Entreprise::select('id', 'horraires_ouverture', 'status')->where('status', '!=', 'ARRET')->get();

		$jours = [
			'Monday' => 'L',
			'Tuesday' => 'Ma',
			'Wednesday' => 'Me',
			'Thursday' => 'J',
			'Friday' => 'V',
			'Saturday' => 'S',
			'Sunday' => 'D',
		];

		Carbon::setLocale('fr');
		$heure = Carbon::now()->format('H:i');
		$heure_actuel = Carbon::parse($heure)->hour;
		$minute_actuel = Carbon::parse($heure)->minute;
		$day = Carbon::now()->localeDayOfWeek;

		foreach ($entreprises as $entreprise)
		{
			$horraires = explode(';', $entreprise['horraires_ouverture'][$jours[$day]]);
			$heure_ouv_matin = Carbon::parse($horraires[0])->hour;			$minute_ouv_matin = Carbon::parse($horraires[0])->minute;
			$heure_ferm_matin = Carbon::parse($horraires[1])->hour;			$minute_ferm_matin = Carbon::parse($horraires[1])->minute;
			$heure_ouv_aprem = Carbon::parse($horraires[2])->hour;			$minute_ouv_aprem = Carbon::parse($horraires[2])->minute;
			$heure_ferm_aprem = Carbon::parse($horraires[3])->hour;			$minute_ferm_aprem = Carbon::parse($horraires[3])->minute;

			if ($heure_ouv_matin <= $heure_actuel && $heure_ferm_matin >= $heure_actuel &&
				(($heure_ouv_matin == $heure_ferm_matin && $minute_ouv_matin <= $minute_actuel && $minute_ferm_matin > $minute_actuel)
					||
					($heure_ouv_matin != $heure_ferm_matin && $heure_ouv_matin == $heure_actuel && $minute_ouv_matin <= $minute_actuel)
					||
					($heure_ouv_matin != $heure_ferm_matin && $heure_ferm_matin == $heure_actuel && $minute_ferm_matin > $minute_actuel)
					||
					($heure_ouv_matin != $heure_ferm_matin && $heure_ferm_matin != $heure_actuel &&
						$heure_ouv_matin != $heure_actuel && $heure_actuel > $heure_ouv_matin &&
						$heure_actuel < $heure_ferm_matin)))
			{
				//Horraires d'ouverture de la matinée
				if ($entreprise->status == 'FERME'
					||
					($entreprise->status == 'FERME_J' && $heure_ouv_matin == $heure_actuel && $minute_ouv_matin == $minute_actuel))
					Entreprise::where('status', '!=', 'ARRET')
						->where(['id' => $entreprise['id']])
						->update(['status' => 'OUVERT']);
			}
			else if ($heure_ouv_aprem <= $heure_actuel && $heure_ferm_aprem >= $heure_actuel &&
				(($heure_ouv_aprem == $heure_ferm_aprem && $minute_ouv_aprem <= $minute_actuel && $minute_ferm_aprem > $minute_actuel)
					||
					($heure_ouv_aprem != $heure_ferm_aprem && $heure_ouv_aprem == $heure_actuel && $minute_ouv_aprem <= $minute_actuel)
					||
					($heure_ouv_aprem != $heure_ferm_aprem && $heure_ferm_aprem == $heure_actuel && $minute_ferm_aprem > $minute_actuel)
					||
					($heure_ouv_aprem != $heure_ferm_aprem && $heure_ferm_aprem != $heure_actuel &&
						$heure_ouv_aprem != $heure_actuel && $heure_actuel > $heure_ouv_aprem &&
						$heure_actuel < $heure_ferm_aprem)))
			{
				//Horraires d'ouverture de l'apres midi
				if ($entreprise->status == 'FERME')
					Entreprise::where('status', '!=', 'ARRET')
						->where(['id' => $entreprise['id']])
						->update(['status' => 'OUVERT']);
			}
			else
			{
				if($entreprise->status == 'OUVERT')
					Entreprise::where('status', '!=', 'ARRET')
						->where(['id' => $entreprise['id']])
						->update(['status' => 'FERME']);
			}
		}
		echo "Ouverture et fermeture des entreprises éffectué.";
	}

	/**
	 * Verification si le produit a deja etait associé a l'entreprise (protection contre les doublons de l'ajout de produit dans la DB)
	 * @param $list_entreprise_produits
	 * @param $new_produit
	 * @return bool
	 */
	public function produitEntrepriseAlreadyExist($list_entreprise_produits, $new_produit)
	{
		foreach ($list_entreprise_produits as $list_entreprise_produit)
			if (isset($list_entreprise_produit['nom']) && isset($list_entreprise_produit['marque']['id']) &&
				$list_entreprise_produit['nom'] == $new_produit->nom &&
				$list_entreprise_produit['marque']['id'] == $new_produit->marque->id)
				return true;
		return false;
	}

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation de l'id entreprise en fonction de l'id user sauvegardé lors de la connection
	 * @return mixed
	 */
	public function getEntrepriseId()
	{
		return Entreprise::where('user_id', Auth::user()->id)->first()->id;
	}

	/**
	 * Recuperation de la liste des produits de l'entreprise
	 * @return mixed
	 */
	public function getEntrepriseProduitsList()
	{
		return (Entreprise::where(['user_id' => Auth::user()->id])->first())->liste_produits;
	}

	/**
	 * Recuperation des produits d'une entreprise en fonction de son id
	 * (pour le calcule du total se basant sur les produits d'une entreprise)
	 * @return mixed
	 */
	public function getEntrepriseProduitsListByID($id_entreprise)
	{
		return (Entreprise::where(['id' => $id_entreprise])->first())->liste_produits;
	}

	/**
	 * Retourne la liste des rayon, sous-rayons et etageres dans l'ordre ou l'entreprise l'a setter
	 * @return mixed
	 */
	public function getShopOrder()
	{
		return (Entreprise::where(['user_id' => Auth::user()->id])->first())->shop_order;
	}

	/**
	 * Recuperation de l'id entreprise en se basant sur le nom de l'entreprise passé en paramettre.
	 * @param $ville
	 * @param $nom
	 * @return null
	 */
	public function getEntrepriseInfosByName($ville, $nom)
	{
		if ($entreprise = Entreprise::select('id', 'shop_order', 'liste_produits', 'ville_id', 'status')
			->where(['nom_enseigne' => $nom])
			->with(['ville' => function ($query) use ($ville)
			{
				return $query->where(['nom' => $ville]);
			}])
			->first())
			return $entreprise;
		return null;
	}

	/**
	 * Recuperation des informations public (non-sensible) d'informations entreprise non soumis a un acces securisé
	 * @param $ville
	 * @param $nom
	 * @return null
	 */
	public function getPublicEntrepriseInfosByName($ville, $nom)
	{
		if ($entreprise = Entreprise::select('id', 'path_file_logo_entreprise', 'banniere', 'nom_enseigne', 'type_activite_id', 'type_entreprise_id', 'addresse_entreprise_contact_id', 'description', 'siret', 'ville_id', 'horraires_ouverture', 'status', 'reseaux_sociaux')
			->where(['nom_enseigne' => $nom])
			->with(['ville' => function ($query) use ($ville)
			{
				return $query->where(['nom' => $ville]);
			}], 'typeActivite', 'typeEntreprise', 'addresseEntreprise')
			->first())
			return $entreprise;
		return null;
	}

	/**
	 * Retourne les informations liées a l'entreprise dans le detail par son proprietaire
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function showEntrepriseInformations()
	{
		if (Auth::check() && !empty(($entreprise = Entreprise::where('user_id', Auth::user()->id)
				->with('user', 'abonnement', 'typeActivite', 'typeEntreprise', 'ville', 'addresseEntreprise', 'contactEntreprise')
				->first())) && $this->authorize('proprietary', $entreprise))
		{
			$entreprise->user->user_type_id = 'Entreprise';
			$entreprise["nb_products_use"] = $this->countProduitsUtiliseDansEtageres($entreprise->id);
			return Response::json($entreprise);
		}
		return Response::json([
			'user' =>
				['user_type_id' => 'Guest']
		]);
	}

	/**
	 * Permet la recuperation d'informations detaillées sur une entreprise en particulié (acces admin et dev)
	 * @param $id
	 * @return mixed
	 */
	public function showDetailsEntrepriseInformations($id)
	{
		$this->authorize('isAdmin', Entreprise::class);
		if (!empty(($entreprise = Entreprise::where('id', $id)
			->with('user', 'abonnement', 'typeActivite', 'typeEntreprise', 'ville', 'addresseEntreprise', 'contactEntreprise')
			->first())))
		{
			$entreprise["nb_products_use"] = $this->countProduitsUtiliseDansEtageres($id);
			return Response::json($entreprise);
		}
		return Response::json([
			'user' =>
				[
					'user_type_id' => 'Admin',
					'api_token' => app('App\Http\Controllers\UserInformationsController')->getAdminToken()
				]
		]);
	}

	/**
	 * Recuperation des entreprises ayant dans leurs etageres des produits d'une certaine categorie ou famille
	 * Si la $famille_id est > 0 on recherche les categories sinon a -1 on liste les familles (les ids commencant a 1 si <= 0, pas d'id specifié)
	 * @param $famill_id
	 * @return array|mixed
	 */
	public function getEntrepriseListOrderByFamilleOrCategoriesID($famille_id)
	{
		$entreprise_list = Entreprise::select('id', 'path_file_logo_entreprise', 'nom_enseigne', 'ville_id', 'liste_produits')
			->with('ville')
			->where(['status' => 'OUVERT'])
			->get();

		if ($famille_id > 0)
			$f_c_list = app('App\Http\Controllers\CategorieProduitController')->show($famille_id);
		else
			$f_c_list = app('App\Http\Controllers\FamilleProduitController')->index();
		$f_c_entreprises = [];

		foreach ($f_c_list as $f_c)
		{
			if ($f_c['id'] > 0)
			{
				$f_c_entreprises[$f_c['id']] = $famille_id > 0 ? ['categorie_id' => $f_c['id']] : ['famille_id' => $f_c['id']];
				$f_c_entreprises[$f_c['id']]['shops'] = [];
				foreach ($entreprise_list as $entreprise)
				{
					$f_c_entreprises = $this->getEntrepriseListMatchFromEtagereProductsInfos([
						'stockage' => $f_c_entreprises,
						'type' => $famille_id > 0 ? "categorie" : "famille",
						'type_id' => $f_c['id'],
						'entreprise_id' => $entreprise['id'],
						'nom_enseigne' => $entreprise['nom_enseigne'],
						'ville_nom' => $entreprise['ville']['nom'],
						'path_file_logo_entreprise' => $entreprise['path_file_logo_entreprise'],
						'entreprise_produits' => $entreprise['liste_produits']
					]);
				}
			}
		}
		return $f_c_entreprises;
	}

	/**
	 * Recuperation des entreprise qui ont des produit d'un certain type (fammille ou categorie) dans leurs etageres
	 * @param $datas
	 * @return mixed
	 */
	private function getEntrepriseListMatchFromEtagereProductsInfos($datas, $limit_entreprise = 0)
	{
		$etageres = app('App\Http\Controllers\EtagereController')->getProduitsEtagereListFor($datas['entreprise_id']);

		foreach ($etageres as $etagere)
		{
			$etageres_detaillee = app('App\Http\Controllers\EtagereController')->getProduitsDetailsToEtagereList($etagere["list_produits"], $datas['entreprise_produits']);
			foreach ($etageres_detaillee as $produits)
			{
				if (isset($produits["id_produit"]) && $datas['type_id'] === $produits['id_produit'][$datas['type']]['id'])
				{
					if (isset($datas["stockage"][$datas['type_id']]['shops'][$datas['entreprise_id']]))
					{
						$datas["stockage"][$datas['type_id']]['shops'][$datas['entreprise_id']]['count'] = $datas["stockage"][$datas['type_id']]['shops'][$datas['entreprise_id']]['count'] + 1;
					}
					else
					{
						if ($limit_entreprise == 0 || sizeof($datas["stockage"][$datas['type_id']]['shops']) < $limit_entreprise)
							$datas["stockage"][$datas['type_id']]['shops'][$datas['entreprise_id']] =
								[
									'id' => $datas['entreprise_id'],
									'nom_enseigne' => $datas['nom_enseigne'],
									'ville' => $datas['ville_nom'],
									'path_file_logo_entreprise' => $datas['path_file_logo_entreprise'],
									'count' => 1
								];
					}
				}
			}
		}
		return $datas["stockage"];
	}

	/**
	 * Retourne le nombre maximum de rayon sous-rayon et etageres en fonction de l'abonnement de l'entreprise
	 * (MIS EN PAUSE EN ATTENDANT L'IMPLEMENTATION DE LIMITES D'ABONNEMENT)
	 * @param $element
	 * @return array|int
	 */
//	public function getAbonnementLimitationFor($element)
//	{
//		if (Auth::check() && !empty($entreprise = Entreprise::where('user_id', Auth::user()->id)
//			->with('user', 'abonnement', 'typeActivite', 'typeEntreprise', 'ville', 'addresseEntreprise', 'contactEntreprise')
//			->first()))
//		{
//			switch($element)
//			{
//				case 'Rayons': return [
//					"entreprise_id" => $entreprise->id,
//					"abo_nb_max_rayons" => $entreprise->abonnement->nb_max_rayons
//				];
//				case 'Sous_Rayons': return [
//					"entreprise_id" => $entreprise->id,
//					"abo_nb_max_sous_rayons" => $entreprise->abonnement->nb_max_sous_rayons
//				];
//				case 'Etageres': return [
//					"entreprise_id" => $entreprise->id,
//					"abo_nb_max_etageres" => $entreprise->abonnement->nb_max_etageres
//				];
//				case 'Produits': return [
//					"entreprise_id" => $entreprise->id,
//					"abo_nb_max_produits" => $entreprise->abonnement->nb_max_produits
//				];
//				default: abort(400, "Element d'abonnement non trouvé");
//			}
//		}
//		abort(400, 'Entreprise non trouvé');
//	}

	/**
	 * Liste des entreprises par filtre et moteur de recherche
	 * @param Request $request
	 * @return mixed
	 */
	public function getEntrepriseList(Request $request)
	{
		$request_cond = [];
		if (!empty($request->nom_enseigne))
			array_push($request_cond, ['nom_enseigne', 'LIKE', $request->nom_enseigne . '%']);

		if (!empty($request->ville_id) && $request->ville_id != 0)
			array_push($request_cond, ['ville_id' , '=', $request->ville_id]);

		if (!empty($request->type_activite_id) && $request->type_activite_id != 0)
			array_push($request_cond,['type_activite_id' , '=', $request->type_activite_id]);

		$entreprise_list = Entreprise::select('status', 'nom_enseigne', 'user_id', 'id', 'ville_id', 'type_activite_id', 'type_entreprise_id')
			->with('ville', 'typeActivite', 'user')
			->when($request_cond, function ($query) use ($request_cond)
			{
				return $query->where($request_cond);
			},function ($query)
			{
				return $query;
			})
			->when($request->status, function ($query) use ($request)
			{
				if (!empty($request->status) && !empty($status_users = User::where('status', $request->status)->pluck('id')->all()))
					return $query->whereIn('user_id', $status_users);
				else
					return $query->where(['status' => $request->status]);

			},function ($query) use ($request)
			{
				return $query;
			})
			->limit(20)
			->get();

		//Formattage du retour au client
		foreach ($entreprise_list as $entreprise)
		{
			$tmp_data = $entreprise['ville']['nom'];
			$tmp_data2 = $entreprise['typeActivite']['nom'];
			$tmp_data3 = $entreprise['user']['status'] .' / '. $entreprise['status'];

			unset($entreprise['ville']);
			unset($entreprise['typeActivite']);
			unset($entreprise['user']);

			$entreprise['ville'] = $tmp_data;
			$entreprise['typeActivite'] = $tmp_data2;
			$entreprise['status'] = $tmp_data3;
		}
		return $entreprise_list;
	}

	/**
	 * Permet de connaitre le nombre de produits utilisé dans les etageres de l'entreprise courante
	 * @return int
	 */
	public function countProduitsUtiliseDansEtageres($entreprise_id)
	{
		$produits_etagere = app('App\Http\Controllers\EtagereController')->getProduitsEtagereListFor($entreprise_id)->map(function ($etagere) { return $etagere['list_produits'];	});
		$produits_etagere_list = [];

		foreach ($produits_etagere as $etageres)
			foreach ($etageres as $etagere)
				if (isset($etagere["id_produit"]) && !isset($produits_etagere_list[$etagere["id_produit"]]) && empty($produits_etagere_list[$etagere["id_produit"]]))
					$produits_etagere_list[$etagere["id_produit"]] = 0;

		return sizeof($produits_etagere_list);
	}

	/**
	 * UPDATES
	 */

	/**
	 * Update les informations liées a l'entreprise ainsi que les contacts qui lui sont liés (add_entreprise et contact_entreprise)
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateEntreprise(Request $request)
	{
		/**modification specifique aux Entreprises*/
		if ($request->id != 0)
			$entreprise = Entreprise::where('id', $request->id)->with('user', 'abonnement', 'typeActivite', 'typeEntreprise', 'ville', 'addresseEntreprise', 'contactEntreprise')->first();
		else
			$entreprise = Entreprise::where('user_id', $request->user_id)->with('user', 'abonnement', 'typeActivite', 'typeEntreprise', 'ville', 'addresseEntreprise', 'contactEntreprise')->first();

		if ($this->authorize('proprietary', $entreprise))
		{
			//ENTREPRISE ACCES ADMIN
			$entreprise->abonnement_id = !empty($request->abonnement) && Auth::user()->isAdmin() ? app('App\Http\Controllers\AbonnementController')->getAbonnementId($request->abonnement) : $entreprise->abonnement_id;
			$entreprise->type_entreprise_id = !empty($request->type_entreprise) && Auth::user()->isAdmin() ? app('App\Http\Controllers\TypeEntrepriseController')->getTypeEntrepriseId($request->type_entreprise) : $entreprise->type_entreprise_id;
			$entreprise->ville_id = !empty($request->ville_id) && Auth::user()->isAdmin() ? $request->ville_id : $entreprise->ville_id;

			//ENTREPRISE
			$entreprise->type_activite_id = !empty($request->type_activite) ? app('App\Http\Controllers\TypeActiviteController')->getTypeActiviteId($request->type_activite) : $entreprise->type_activite_id;
			$entreprise->description = !empty($request->description) ? $request->description : $entreprise->description;
			$entreprise->horraires_ouverture = !empty($request->horraires_ouverture) ? $request->horraires_ouverture : $entreprise->horraires_ouverture;
			$entreprise->panier_min_general = !empty($request->panier_min_general) ? $request->panier_min_general : $entreprise->panier_min_general;
			$entreprise->status = !empty($request->status) ? $request->status : $entreprise->status;
			$entreprise->liste_produits = !empty($request->new_produit) ? $this->updateProduitsEntreprise($request->new_produit) : $entreprise->liste_produits;
			if (empty($entreprise->reseaux_sociaux))
			{
				$entreprise->reseaux_sociaux = [
					"facebook" => "",
					"instagram" => "",
					"twitter" => "",
					"pinterest" => ""
				];
			}
			$entreprise->reseaux_sociaux = !empty($request->reseaux_sociaux) ? $request->reseaux_sociaux : $entreprise->reseaux_sociaux;

			if (!empty($request->path_file_logo_entreprise))
			{
				$old_img_path = substr($entreprise->path_file_logo_entreprise, strlen(config('app.url')."/storage/"));
				if (strcmp($old_img_path, "bobby_images/enseigne/default.svg") != 0)
					app('App\Http\Controllers\FileManager')->deleteImageFile($old_img_path);
				$file_name = "logo_" . $entreprise->nom_enseigne;
				$entreprise->path_file_logo_entreprise = app('App\Http\Controllers\FileManager')->uploadNewImageFile($request->path_file_logo_entreprise, $file_name, "bobby_images/enseigne",
					[
						'larg' => 310,
						'long' => 200
					]);
			}

			if (!empty($request->banniere))
			{
				$old_img_path = substr($entreprise->banniere, strlen(config('app.url')."/storage/"));
				if ($old_img_path != "bobby_images/enseigne/default-banner.svg")
					app('App\Http\Controllers\FileManager')->deleteImageFile($old_img_path);
				$file_name = "banniere_" . $entreprise->nom_enseigne;
				$entreprise->banniere = app('App\Http\Controllers\FileManager')->uploadNewImageFile($request->banniere, $file_name, "bobby_images/enseigne",
					[
						'larg' => 600,
						'long' => 300
					]);
			}

			$entreprise->save();
			//ENTREPRISE ADDRESSE
			app('App\Http\Controllers\ContactController')->updateContact(
				[
					'id' => $entreprise->addresse_entreprise_contact_id,
					'addresse' => $request->addresse,
					'code_postal' => $request->code_postal,
					'commune' => $request->commune,
					'email_fact' => $request->email_fact,
					'addresse_fact' => $request->addresse_fact,
					'code_postal_fact' => $request->code_postal_fact,
					'commune_fact' => $request->commune_fact
				]
			);

			//CONTACT
			app('App\Http\Controllers\ContactController')->updateContact(
				[
					'id' => $entreprise->contact_entreprise_id,
					'nom' => $request->nom,
					'prenom' => $request->prenom,
					'telephone' => $request->telephone,
					'email' => $request->email
				]
			);

			abort(204, "Entreprise updated.");
		}
		abort(400, "Utilisateur non trouvé");
	}

	/**
	 * Mise a jour des information liées a l'ordre des rayon, sous-rayon, etagere et produits
	 * @param Request $request
	 */
	public function updateShopOrder(Request $request)
	{
		if (!empty($entreprise = Entreprise::where(['user_id' => Auth::user()->id])->first()) &&
			$this->authorize('proprietary', $entreprise))
		{
			$updated_shop_order = [];

			//revoir le parsing re-travailler coté client si necessaire pour optimisation
			$lettre_rayon = 'A';
			$counter = 0;

			foreach ($request->shop_order as $i => $rayon)
			{
				$updated_shop_order[$lettre_rayon . $i]["rayon_id"] = $rayon["rayon_id"];
				$updated_shop_order[$lettre_rayon . $i]["rayon_nom"] = $rayon["rayon_nom"];
				foreach ($rayon["sous_rayons"] as $j => $sous_rayon)
				{
					$updated_shop_order[$lettre_rayon . $i][$lettre_rayon . $i . $j]["sous_rayon_id"] = $sous_rayon["sous_rayon_id"];
					$updated_shop_order[$lettre_rayon . $i][$lettre_rayon . $i . $j]["sous_rayon_nom"] = $sous_rayon["sous_rayon_nom"];
					foreach ($sous_rayon["etageres"] as $k => $etagere)
					{
						$updated_shop_order[$lettre_rayon . $i][$lettre_rayon . $i . $j][$lettre_rayon . $i . $j . $k]["etagere_id"] = $etagere["etagere_id"];
						$updated_shop_order[$lettre_rayon . $i][$lettre_rayon . $i . $j][$lettre_rayon . $i . $j . $k]["etagere_nom"] = $etagere["etagere_nom"];
					}
				}
				if ($counter == 25) {
					$counter = 0;
					++$lettre_rayon;
				}
				++$counter;
			}
			$entreprise->shop_order = $updated_shop_order;
			$entreprise->save();
			abort(200, 'Shop order mis a jour.');
		}
		abort(400, "L'entreprise n'a pas pu etre trouvee.");
	}

	/**
	 * Mise a jour des informations liées aux produits de l'entreprise
	 * @param $new_produit
	 * @return mixed
	 */
	public function updateProduitsEntreprise(Request $request)
	{
		if ($request && !empty($entreprise = Entreprise::where(['user_id' => Auth::user()->id])->first()) &&
			$this->authorize('proprietary', $entreprise))
		{
			$tmp_list_produits = $entreprise->liste_produits;

			foreach ($tmp_list_produits as $i => $entreprise_produit)
			{
				if ($entreprise_produit['id'] == $request->new_produit["id"])
				{
					if ($this->checkStockValues($request->new_produit["stocks"]))
					{
						unset($tmp_list_produits[$i]);
						$tmp_list_produits[$i] = $request->new_produit;
						$tmp_list_produits[$i]["stocks"] = $this->addVolumeToStocks($request->new_produit["stocks"]);
						$entreprise->liste_produits = $tmp_list_produits;
						$entreprise->save();
						return $entreprise->liste_produits;
					}
					else
						break;
				}
			}
		}
		abort(400, "Le produit n'a pas pus etre modifier.");
	}

	/**
	 * Mise a jour des stocks de l'entreprise suite au paiement d'un panier d'un client
	 * @param $datas
	 * @return bool
	 */
	public function updateStockAfterPaiementEntreprise($datas)
	{
		if ($entreprise = Entreprise::select('id', 'liste_produits', 'ville_id')
			->where(['nom_enseigne' => $datas['entreprise_nom']])
			->with(['ville' => function ($query) use ($datas)
			{
				return $query->where(['nom' => $datas['ville_nom']]);
			}])
			->first())
		{
			$list_produits = $entreprise->liste_produits;
			foreach ($list_produits as $i => $produit)
			{
				if ($produit['ref_produit'] == $datas['ref_produit'])
				{
					foreach ($produit['stocks'] as $j => $stock) {
						if ($stock['id'] == $datas['id_stock'])
						{
							$list_produits[$i]['stocks'][$j]['stock'] -= $datas['quantite'];
							if ($list_produits[$i]['stocks'][$j]['stock'] <= 0)
							{
								$list_produits[$i]['stocks'][$j]['activer'] = false;
								$list_produits[$i]['stocks'][$j]['afficher'] = false;
							}
							break;
						}
					}
					break;
				}
			}
			$entreprise->liste_produits = $list_produits;
			$entreprise->save();
			return true;
		}
		abort(400, 'Erreure durant la mise a jour des stocks.');
	}

	/**
	 * Modification des produits se trouvant dans les entreprise afin de mettre a jour si necessaire
	 * (la description peut etre re-setter par les entreprises, si la description a etait modifiée elle ne sera pas update,
	 * les objets correspondant au marque, famille, categorie, type seront egalement mis a jour si un id est modifié)
	 * Peut permettre la re-actualisation des informations d'un produit generique dans les entreprises ayant ce produit afin
	 * que la modification se fasse partout.
	 * @param $ref_produit
	 * @param $new_produit
	 * @param $old_produit_description
	 */
	public function updateEntreprisesProduitInformations($ref_produit, $new_produit, $old_produit_description = null)
	{
		$entreprises = Entreprise::select('id','liste_produits')->get();
		$list_modification = [];

		foreach ($entreprises as $i => $entreprise)
		{
			foreach ($entreprise['liste_produits'] as $j => $produit)
			{
				$tmp_save_liste_produits = $entreprise['liste_produits'];
				if ($produit['ref_produit'] == $ref_produit)
				{
					if (isset($new_produit['nom']))
						$tmp_save_liste_produits[$j]['nom'] =  $new_produit['nom'];
					//Modifier les ids si different et remplacer les informations des objets marque, famille,... ainsi que leurs ids
					if (isset($new_produit['famille_id']) && $tmp_save_liste_produits[$j]['famille']['id'] != $new_produit['famille_id'])
					{
						$tmp_save_liste_produits[$j]['famille'] = app('App\Http\Controllers\FamilleProduitController')
							->getFamille($new_produit['famille_id']);
					}
					if (isset($new_produit['categorie_id']) && $tmp_save_liste_produits[$j]['categorie']['id'] != $new_produit['categorie_id'])
					{
						$tmp_save_liste_produits[$j]['categorie'] = app('App\Http\Controllers\CategorieProduitController')
							->getCategorie($new_produit['categorie_id']);
					}
					if (isset($new_produit['type_id']) && $tmp_save_liste_produits[$j]['type']['id'] != $new_produit['type_id'])
					{
						$tmp_save_liste_produits[$j]['type'] = app('App\Http\Controllers\TypeProduitController')
							->getType($new_produit['type_id']);
					}
					if (isset($new_produit['marque_id']) && $tmp_save_liste_produits[$j]['marque'] != app('App\Http\Controllers\MarqueProduitController')
							->getMarque($new_produit['marque_id'])['nom'])
					{
						$tmp_save_liste_produits[$j]['marque'] = app('App\Http\Controllers\MarqueProduitController')
							->getMarque($new_produit['marque_id'])['nom'];
					}

					$tmp_save_liste_produits[$j]['status'] = isset($new_produit['status']) ? $new_produit['status'] : $tmp_save_liste_produits[$j]['status'];
					$tmp_save_liste_produits[$j]['entreprise_id'] = isset($new_produit['entreprise_id']) ? $new_produit['entreprise_id'] : $tmp_save_liste_produits[$j]['entreprise_id'];
					$tmp_save_liste_produits[$j]['poids'] = isset($new_produit['poids']) ? $new_produit['poids'] : $tmp_save_liste_produits[$j]['poids'];
					$tmp_save_liste_produits[$j]['longueur'] = isset($new_produit['longueur']) ? $new_produit['longueur'] : $tmp_save_liste_produits[$j]['longueur'];
					$tmp_save_liste_produits[$j]['largeur'] = isset($new_produit['largeur']) ? $new_produit['largeur'] : $tmp_save_liste_produits[$j]['largeur'];
					$tmp_save_liste_produits[$j]['hauteur'] = isset($new_produit['hauteur']) ? $new_produit['hauteur'] : $tmp_save_liste_produits[$j]['hauteur'];
					$tmp_save_liste_produits[$j]['volume'] = isset($new_produit['volume']) ? $new_produit['volume'] : $tmp_save_liste_produits[$j]['volume'];
					$tmp_save_liste_produits[$j]['unite_mesure'] = isset($new_produit['unite_mesure']) ? $new_produit['unite_mesure'] : $tmp_save_liste_produits[$j]['unite_mesure'];

					//savegarde de la liste des produits de l'entreprise dont celui mis a jour
					if (isset($new_produit['description']) && $tmp_save_liste_produits[$j]['description'] == $old_produit_description)
						$tmp_save_liste_produits[$j]['description'] = $new_produit['description'];

					//modification des images produit dans l'entreprise apres un nouveau upload ou non
					$tmp_save_liste_produits[$j]['path_file_photo_principale'] = isset($new_produit['path_file_photo_principale']) ? $new_produit['path_file_photo_principale'] : $tmp_save_liste_produits[$j]['path_file_photo_principale'];
					$tmp_save_liste_produits[$j]['path_file_photos_secondaire'] = isset($new_produit['path_file_photos_secondaire']) ? $new_produit['path_file_photos_secondaire'] : $tmp_save_liste_produits[$j]['path_file_photos_secondaire'];

					$list_modification[$entreprise['id']] =
						[
							'entreprise_id' => $entreprise['id'],
							'list_produits' => $tmp_save_liste_produits
						];
					break;
				}
			}
		}

		//parcour de la sauvegarde des listes de produit afin d'update la liste de produits individuellement pour chaques entreprises
		foreach ($list_modification as $modification)
		{
			Entreprise::where(['id' => $modification['entreprise_id']])
				->update(['liste_produits' => json_encode($modification['list_produits'])]);
		}
	}

	/**
	 * DELETE
	 */
	/**
	 * Suppressioin d'un produit appartenant à l'entreprise par un admin
	 * @param Request $request
	 * @return mixed
	 */
	public function deleteProduitsEntreprise(Request $request)
	{
		$entreprise = Entreprise::where(['id' => $request->id_entreprise])->first();

		$list_entreprise_produits = $entreprise->liste_produits;

		foreach ($list_entreprise_produits as $i => $list_entreprise_produit)
			if ($list_entreprise_produit['id'] == $request->id_produit)
			{
				app('App\Http\Controllers\EtagereController')->deleteProduitInAllEtageres($request->id_entreprise, $request->id_produit);
				unset($list_entreprise_produits[$i]);
				$entreprise->liste_produits = $list_entreprise_produits;
				$entreprise->save();

				//voir pour personnaliser le retour de l'entreprise sur la re-actualisation url de retour persso renseigner juste 200 si ok
				return $entreprise->liste_produits;
			}
		abort(400, 'Produit non trouve.');
	}
}
