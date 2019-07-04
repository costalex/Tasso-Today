<?php

namespace App\Http\Controllers;

use function abort;
use App\Produit;
use function array_push;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use function json_encode;
use function str_random;
use function str_replace;

use function strstr;

class ProduitController extends Controller
{
	/**
	 * STOCKAGES ET REMPLACEMENTS
	 */

    /**
     * Ajout d'un produit
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $errors_messages = [
	    	'required' => 'The :attribute field is required.',
		    'string' => 'The :attribute must already have letters.',
		    'max' => 'The :attribute input is too long.'
	    ];

	    Validator::make($request->all(), [
		    'nom' => 'required|string|max:50',
		    'description' => 'required|string',
		    'famille_id' => 'required',
		    'categorie_id' => 'required',
		    'marque_id' => 'required',
		    'poids' => 'required',
		    'status' => 'required',
		    'path_file_photo_principale' => 'required',
		    'longueur' => 'required',
		    'largeur' => 'required',
		    'hauteur' => 'required',
		    'volume' => 'required',
		    'unite_mesure' => 'required'
	    ],$errors_messages)->validate();

	    $produit = [
		    'nom' => $request->nom,
		    'description' => $request->description,
		    'famille_id' => $request->famille_id,
		    'categorie_id' => $request->categorie_id,
		    'type_id' => $request->type_id,
		    'marque_id' => $request->marque_id,
		    'entreprise_id' => $request->status == 'PRIVE' ? $request->id_entreprise : 0,
		    'poids' => $request->poids,
		    'status' => $request->status,
		    'path_file_photo_principale' => $this->stockImages($request->path_file_photo_principale[0], "PRINCIPALE"),
		    'path_file_photos_secondaire' => !empty($request->path_file_photos_secondaire) ? $this->stockImages($request->path_file_photos_secondaire, "SECONDAIRE") : "",
		    'longueur' => $request->longueur,
		    'largeur' => $request->largeur,
		    'hauteur' => $request->hauteur,
		    'volume' => $request->volume,
		    'ref_produit' => str_random(6),
		    'unite_mesure' =>$request->unite_mesure
	    ];

		if ($request->marque_id > 0 && empty((Produit::where(['nom' => $request->nom,'marque_id' => $request->marque_id])->first())->id))
	    {
		    $new_produit = Produit::firstOrCreate($produit);

		    $produit["ref_produit"] = $this->gerenateUniqueProduitReference($new_produit);

	    	$request = new Request([
				'id_produit' => $new_produit->id,
				'id_entreprise' => $request->id_entreprise,
			]);

			app('App\Http\Controllers\EntrepriseInformationsController')->addProduitsEntreprise($request);

			app('App\Http\Controllers\FileManager')->migrationProduitsSave($produit);
		}
		else
			abort(400, 'Produit existe deja.');

		abort(204, 'ok');
    }

	/**
	 * Generation d'un reference unique lors de la creation d'un produit generique
	 * @param Produit $produit
	 * @return mixed|string
	 */
    private function gerenateUniqueProduitReference(Produit $produit)
    {
	    if (($taille_id = strlen((string)$produit->id)) < 6)
		    $produit->ref_produit = $produit->id.str_random(6-$taille_id);
	    else
		    $produit->ref_produit = $produit->id.str_random(1);
	    $produit->save();
	    return $produit->ref_produit;
    }

	/**
	 * WARNING : Si il y a une modification du nombre de miniature a generer modifier aussi le template => templateProduit dans FileManager.php
	 */
	/**
	 * Upload des images sur le serveur
	 * @param $images
	 * @param $type
	 * @return mixed
	 */
    private function stockImages($images, $type)
    {
	    $images_list = [];
	    if ($type == "PRINCIPALE")
	    {
		     array_push($images_list, app('App\Http\Controllers\FileManager')
			    ->uploadImageFile($images, 'principale_'.Carbon::now().'_'.str_random(16), "images_produits", [
			    	"1" => 50,
			    	"2" => 200,
			    	"3" => 255
			    ]));
	    }
        else
	    {
			foreach ($images as $image)
			{
				array_push($images_list, app('App\Http\Controllers\FileManager')
					->uploadImageFile($image, 'secondaire_'.Carbon::now().'_'.str_random(16), "images_produits", [
						"1" => 50,
						"2" => 200,
						"3" => 255
					]));
			}
	    }
	return $images_list;
  }

	/**
	 * Suppresion de l'ancienne image d'un produit, upload et linkage de la nouvelle image
	 * @param $image_type
	 * @param $old_image_path
	 * @param $new_image_path
	 * @return mixed|string
	 */
    public function replaceOldImages($image_type, $old_image_path, $new_image_path)
    {
		if (!empty($old_image_path))
			{
				foreach ($old_image_path as $item)
				{
					$image_name = str_replace(url('/') . "/storage/images_produits/", "", $item['image']);

					if (!empty($image_name))
						Storage::disk('public')->delete('images_produits/' . $image_name);
					foreach ($item['image_miniature'] as $image_miniature)
					{
						$image_miniature_name = str_replace(url('/') . "/storage/images_produits/", "", $image_miniature);
						if (!empty($image_miniature_name))
							Storage::disk('public')->delete('images_produits/' . $image_miniature_name);
					}
				}
			}
	    if (empty($new_image_path))
	    	return "";
		return $this->stockImages($new_image_path, $image_type);
    }

	/**
	 * GETTERS
	 */

	/**
	 * Recuperation des informations d'un produit generique en fonction de sa reference
	 * @param $ref_produit
	 * @return mixed
	 */
    public function getGenProduitInfosByRef($ref_produit)
    {
		return Produit::where(['ref_produit' => $ref_produit])->with('famille', 'categorie', 'type', 'marque')->first();
    }

	/**
	 * Recuperation des informations d'un produit generique en fonction de son id
	 * @param $id_produit
	 * @return mixed
	 */
	public function getGenProduitInfosById($id_produit)
	{
		return Produit::where('id', $id_produit)->with('marque', 'famille', 'categorie', 'type')->first();
	}

	/**
	 * Retourne la liste des produits en fonction des filtres demandés
	 * @param Request $request
	 * @return mixed
	 */
	public function produitList(Request $request)
	{
		$request_cond = [];

		if (!empty($request->nom))
			array_push($request_cond, ['nom', 'LIKE', $request->nom . '%']);

		if (!empty($request->famille_id))
			array_push($request_cond, ['famille_id' , '=', $request->famille_id]);

		if (!empty($request->categorie_id))
			array_push($request_cond,['categorie_id' , '=', $request->categorie_id]);

		if (!empty($request->type_id))
			array_push($request_cond,['type_id' , '=', $request->type_id]);

		if (!empty($request->id))
			array_push($request_cond, ['id' , '>', $request->id]);

		$produits_list = Produit::where($request_cond)
			->when($request->id_entreprise, function ($query) use ($request)
			{
				return $query->whereIn('entreprise_id', [0, $request->id_entreprise]);
			})
			->with('marque', 'famille', 'categorie', 'type')
			->select('id', 'nom', 'marque_id', 'path_file_photo_principale', 'ref_produit', 'updated_at')
			->limit(50)
			->get();

		foreach ($produits_list as $produit)
		{
			$tmp_data = $produit['marque']['nom'];
			unset($produit['marque']);
			$produit['marque'] = $tmp_data;
		}
		return $produits_list;
	}

	/**
	 * UPDATES
	 */

	/**
	 * Modification des informations d'un produit generique
	 * @param Request $request
	 */
	public function updateGeneriqueProduitInformations(Request $request)
	{
		if (empty($old_produit = Produit::where([
			'ref_produit' => $request->ref_produit
		])->first()))
			abort(400, "Produit introuvable.");

		$old_produit_description = $old_produit->description;

		$new_produit = [
			'nom' => isset($request->nom) && $request->nom != $old_produit->nom ? $request->nom : $old_produit->nom,
			'description' => isset($request->description) && $request->description != $old_produit->description ? $request->description : $old_produit->description,
			'famille_id' => isset($request->famille_id) && $request->famille_id != $old_produit->famille_id ? $request->famille_id : $old_produit->famille_id,
			'categorie_id' => isset($request->categorie_id) && $request->categorie_id != $old_produit->categorie_id ? $request->categorie_id : $old_produit->categorie_id,
			'type_id' => isset($request->type_id) && $request->type_id != $old_produit->type_id ? $request->type_id : $old_produit->type_id,
			'marque_id' => isset($request->marque_id) &&  $request->marque_id != $old_produit->marque_id ? $request->marque_id : $old_produit->marque_id,
			'status' => isset($request->status) && $request->status != $old_produit->status ? $request->status : $old_produit->status,
			'entreprise_id' => isset($request->status) && $request->status != $old_produit->status  ? $request->status == 'PRIVE' ? $request->id_entreprise : 0  : $old_produit->entreprise_id,
			'poids' => isset($request->poids) && $request->poids != $old_produit->poids ? $request->poids : $old_produit->poids,
			'longueur' => isset($request->longueur) && $request->longueur != $old_produit->longueur ? $request->longueur : $old_produit->longueur,
			'largeur' => isset($request->largeur) && $request->largeur != $old_produit->largeur ? $request->largeur : $old_produit->largeur,
			'hauteur' => isset($request->hauteur) && $request->hauteur != $old_produit->hauteur ? $request->hauteur : $old_produit->hauteur,
			'volume' => isset($request->volume) && $request->volume != $old_produit->volume ? $request->volume : $old_produit->volume,
			'unite_mesure' => isset($request->unite_mesure) && $request->unite_mesure != $old_produit->unite_mesure ? $request->unite_mesure : $old_produit->unite_mesure,
			'path_file_photo_principale' => !empty($request->path_file_photo_principale) ? $this->replaceOldImages("PRINCIPALE", $old_produit->path_file_photo_principale, $request->path_file_photo_principale[0]) : $old_produit->path_file_photo_principale,
			'path_file_photos_secondaire' => !empty($request->path_file_photos_secondaire) ? $this->replaceOldImages("SECONDAIRE", $old_produit->path_file_photos_secondaire, $request->path_file_photos_secondaire) : $old_produit->path_file_photos_secondaire,
		];
		if (empty(Produit::where($new_produit)->first()))
		{
			//modification du produit generique.
			$old_produit->unite_mesure = $new_produit['unite_mesure'];
			$old_produit->update($new_produit);
			$old_produit->save();

			app('App\Http\Controllers\EntrepriseInformationsController')
				->updateEntreprisesProduitInformations($old_produit->ref_produit, $new_produit, $old_produit_description);

			abort(200, "Produit generique mis a jour.");
		}
		abort(400, "Ce nouveau produit existant deja.");
	}

	/**
	 * Mise a jour des tailles des images des produits generique precedement crées
	 */
    public function updateNewSizeForImagesGenProduits(Request $request)
    {
	    $files = Storage::disk('public')->allFiles('images_produits');

	    foreach ($files as $file)
	    {
		    $width = Image::make(public_path('/storage/').$file)->width();
		    $height = Image::make(public_path('/storage/').$file)->height();

		    if ($request->old_images_size == $width && $request->old_images_size == $height)
		    {
			    Image::make(public_path('/storage/').$file)
				    ->resize($request->new_images_size, $request->new_images_size)
				    ->save(public_path('/storage/').$file);
		    }
	    }
	    abort(200, "Taille de l'image mise a jour");
    }

	/**
	 * Modification du nom de domaine des images de produits generique
	 * @param Request $request
	 */
	public function updateImageUrl(Request $request)
	{
		$produits = Produit::select('id', 'ref_produit', 'path_file_photo_principale', 'path_file_photos_secondaire')->get();
		$old_prefix_url = "https://www.tassostore.com";
		$new_prefix_url = "http://217.182.136.139:9000";
//		$new_prefix_url = "";

		foreach ($produits as $produit)
		{
			$tmp_path_file_photo_principale = $produit['path_file_photo_principale'];
			$tmp_path_file_photos_secondaire = $produit['path_file_photos_secondaire'];

			if (strstr($tmp_path_file_photo_principale[0]['image'], $old_prefix_url) != false)
				$tmp_path_file_photo_principale[0]['image'] = str_replace($old_prefix_url, $new_prefix_url, $tmp_path_file_photo_principale[0]['image']);

			foreach ($tmp_path_file_photo_principale[0]['image_miniature'] as $i => $img_min)
			{
				if ($tmp_path_file_photo_principale[0]['image_miniature'][$i] && strstr($tmp_path_file_photo_principale[0]['image_miniature'][$i], $old_prefix_url) != false)
					$tmp_path_file_photo_principale[0]['image_miniature'][$i] = str_replace($old_prefix_url, $new_prefix_url, $tmp_path_file_photo_principale[0]['image_miniature'][$i]);
			}

			if ($tmp_path_file_photos_secondaire)
			{
				foreach ($tmp_path_file_photos_secondaire as $i => $item)
				{
					if ($tmp_path_file_photos_secondaire[$i]['image'] && strstr($tmp_path_file_photos_secondaire[$i]['image'], $old_prefix_url) != false)
						$tmp_path_file_photos_secondaire[$i]['image'] = str_replace($old_prefix_url, $new_prefix_url, $tmp_path_file_photos_secondaire[$i]['image']);

					foreach ($tmp_path_file_photos_secondaire[$i]['image_miniature'] as $j =>$image)
					{
						if ($image && strstr($tmp_path_file_photos_secondaire[$i]['image_miniature'][$j], $old_prefix_url) != false)
							$tmp_path_file_photos_secondaire[$i]['image_miniature'][$j] = str_replace($old_prefix_url, $new_prefix_url, $tmp_path_file_photos_secondaire[$i]['image_miniature'][$j]);
					}
				}
			}
			Produit::where(['id' => $produit['id']])->update([
				'path_file_photo_principale' => json_encode($tmp_path_file_photo_principale),
				'path_file_photos_secondaire' => json_encode($tmp_path_file_photos_secondaire)
			]);

			$new_produit = [
				'path_file_photo_principale' => $tmp_path_file_photo_principale,
				'path_file_photos_secondaire' => $tmp_path_file_photos_secondaire,
			];
			app('App\Http\Controllers\EntrepriseInformationsController')
				->updateEntreprisesProduitInformations($produit->ref_produit, $new_produit);
		}
		abort(200, "prefix des images modifié.");
	}

	/**
	 * Renommage du nom des produits generique
	 * @param Request $request
	 */
	public function updateImageProductName(Request $request)
	{
		$produits = Produit::select('id', 'ref_produit', 'path_file_photo_principale', 'path_file_photos_secondaire')->get();
		$old_prefix_url = ":";
		$new_prefix_url = '_';
		$domain_name = "http://217.182.136.139:9000/storage/images_produits/";

		foreach ($produits as $produit)
		{
			$tmp_path_file_photo_principale = $produit['path_file_photo_principale'];
			$tmp_path_file_photos_secondaire = $produit['path_file_photos_secondaire'];


			if ($tmp_path_file_photo_principale[0]['image'])
			{
				$save_nom = substr($tmp_path_file_photo_principale[0]['image'], 48);
				$tmp_path_file_photo_principale[0]['image'] = $domain_name . str_replace($old_prefix_url, $new_prefix_url, $save_nom);
			}

			foreach ($tmp_path_file_photo_principale[0]['image_miniature'] as $i => $img_min)
				if ($tmp_path_file_photo_principale[0]['image_miniature'][$i])
				{
					$save_nom = substr($tmp_path_file_photo_principale[0]['image_miniature'][$i], 48);
					$tmp_path_file_photo_principale[0]['image_miniature'][$i] = $domain_name . str_replace($old_prefix_url, $new_prefix_url, $save_nom);
				}

			if ($tmp_path_file_photos_secondaire)
			{
				foreach ($tmp_path_file_photos_secondaire as $i => $item)
				{
					if ($tmp_path_file_photos_secondaire[$i]['image'])
					{
						$save_nom = substr($tmp_path_file_photos_secondaire[$i]['image'], 48);
						$tmp_path_file_photos_secondaire[$i]['image'] = $domain_name . str_replace($old_prefix_url, $new_prefix_url, $save_nom);
					}

					foreach ($tmp_path_file_photos_secondaire[$i]['image_miniature'] as $j =>$image)
						if ($tmp_path_file_photos_secondaire[$i]['image_miniature'][$j])
						{
							$save_nom = substr($tmp_path_file_photos_secondaire[$i]['image_miniature'][$j], 48);
							$tmp_path_file_photos_secondaire[$i]['image_miniature'][$j] = $domain_name . str_replace($old_prefix_url, $new_prefix_url, $save_nom);
						}
				}
			}
			Produit::where(['id' => $produit['id']])->update([
				'path_file_photo_principale' => json_encode($tmp_path_file_photo_principale),
				'path_file_photos_secondaire' => json_encode($tmp_path_file_photos_secondaire)
			]);

			$new_produit = [
				'path_file_photo_principale' => $tmp_path_file_photo_principale,
				'path_file_photos_secondaire' => $tmp_path_file_photos_secondaire,
			];
			app('App\Http\Controllers\EntrepriseInformationsController')
				->updateEntreprisesProduitInformations($produit->ref_produit, $new_produit);
		}
		abort(200, "Noms des images modifié.");
	}

	/**
	 * DELETE
	 */

	/**
	 * Suppression d'une image secondaire d'un produit
	 * @param Request $request
	 */
	public function deleteImageProduit(Request $request)
	{
		if (empty($old_produit = Produit::where([
			'ref_produit' => $request->ref_produit
		])->first()))
			abort(400, "Produit introuvable.");

		$tmp_path_file_photos_secondaire = $old_produit->path_file_photos_secondaire;
		$image_found = false;

		foreach ($old_produit->path_file_photos_secondaire as $i => $photos_secondaire)
		{
			foreach ($photos_secondaire as $j => $item)
			{
				if ($item == $request->img_target)
				{
					$image_name = str_replace(url('/') . "/storage/images_produits/", "", $item);
					Storage::disk('public')->delete('images_produits/' . $image_name);
					$tmp_path_file_photos_secondaire[$i][$j] = "";
					$image_found = true;
				}
			}
			if ($image_found)
			{
				foreach ($photos_secondaire['image_miniature'] as $k => $image)
				{
					$image_name = str_replace(url('/') . "/storage/images_produits/", "", $image);
					Storage::disk('public')->delete('images_produits/' . $image_name);
					$tmp_path_file_photos_secondaire[$i]['image_miniature'][$k] = "";
				}
				break;
			}
		}
		if ($image_found)
		{
			app('App\Http\Controllers\EntrepriseInformationsController')
				->updateEntreprisesProduitInformations($request->ref_produit, ['path_file_photos_secondaire' => $tmp_path_file_photos_secondaire]);
			$old_produit->path_file_photos_secondaire = $tmp_path_file_photos_secondaire;
			$old_produit->save();

			abort(200, 'Image produit supprimé');
		}
		abort(400, "Image produit n'a pas pu etre supprimé");
	}
}
