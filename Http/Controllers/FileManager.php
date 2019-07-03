<?php

namespace App\Http\Controllers;

use function abort;
use function array_push;
use function asset;
use function base64_decode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\Facades\Image;
use function preg_match;
use function public_path;

use function strtolower;
use function substr;

class FileManager extends Controller
{
	/**
	 * Fonction d'upload d'images
	 * @param $file
	 * @param $file_name
	 * @param $location
	 * @param bool $miniature
	 * @return array
	 * @throws \Exception
	 */
    public function uploadImageFile($file, $file_name, $location, $image_sizes)
    {
    	//Verification de l'encodage de l'image
	    $parse_result = $this->parseImageBase64($file, $file_name, $location);

		if (!Storage::disk('public')->put($parse_result["full_path"], $parse_result["data"]))
			abort(400, "Can't upload file.");

		return $this->createImageMiniatureFrom($file_name, $parse_result["type"], $parse_result["full_path"], $image_sizes);
    }

	/**
	 * Creation des miniatures de l'image recus aux tailles specifiées
	 * @param $file_name
	 * @param $type
	 * @param $full_path
	 * @param $image_sizes
	 * @return array
	 */
	public function createImageMiniatureFrom($file_name, $type, $full_path, $image_sizes)
	{
		$images_produits_path = public_path('/storage/images_produits/');
		$miniatures_list = [];

		foreach ($image_sizes as $i => $image_size)
		{
			Image::make($images_produits_path.$file_name.".{$type}")
				->resize($image_size,$image_size)
				->save($images_produits_path.$file_name.'_min_'.$i.'-'.".{$type}");
			array_push($miniatures_list, asset('/storage/images_produits/'.$file_name.'_min_'.$i.'-'.".{$type}"));
		}

		return [
			"image" => asset("storage/$full_path"),
			"image_miniature" => $miniatures_list
		];
	}

	/**
	 * Parsing du header de l'image et recuperation des informations
	 * @param $file
	 * @param $file_name
	 * @param $location
	 * @return array
	 * @throws \Exception
	 */
	public function parseImageBase64($file, $file_name, $location)
	{
		if (preg_match('/^data:image\/(\w+);base64,/', $file, $type)) {
			//Recuperation et mise en forme des datas récupérées aupres du header de l'image
			$data = substr($file, strpos($file, ',') + 1);
			$type = strtolower($type[1]);
			$full_path = $location . '/' . $file_name . ".{$type}";

			//Vérification des extensions prisent en charge
//		    if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png']))
//			    throw new \Exception('Invalid image type.');

			//Verification du decodage
			if (($data = base64_decode($data)) === false)
				throw new \Exception('Base64_decode failed.');
			return [
				"full_path" => $full_path,
				"data" => $data,
				"type" => $type
			];
		}
		else
			return [
				"full_path" => "",
				"data" => "",
				"type" => ""
			];;
	}

	/**
	 * Creation d'un fichier de sauvegarde des produits ajouter depuis l'espace admin
	 * en requete afin de pouvoir les ajouter simplement a l'etape de migration
	 * @param $produit
	 */
    public function migrationProduitsSave($produit)
    {
	    Storage::disk('local')->prepend('migrationProduits.txt', $this->templateProduit($produit));
		abort(200, "ok");
    }

	/**
	 * Creation d'un fichier de sauvegarde des marques produits ajouter depuis l'espace admin
	 * @param $marque
	 */
    public function migrationMarquesSave($marque)
	{
		Storage::disk('local')->prepend('migrationMarques.txt', $this->templateMarques($marque));
	}

	/**
	 * TEMPLATES
	 */

	/**
	 * Creation d"une fonction template permettant de sauvegarder la creation d'un produit depuis le site et permet
	 * de copier coller le texte dans la migration afin de le retrouver dans la DB par la suite
	 * @param $produit
	 * @return string
	 */
    public function templateProduit($produit)
    {
	    $image_secondaire["path_file_photos_secondaire"][0]["image"] = !empty($produit["path_file_photos_secondaire"][0]["image"]) ? $produit["path_file_photos_secondaire"][0]["image"] : "";
	    $image_secondaire["path_file_photos_secondaire"][0]["image_miniature"][0] = !empty($produit["path_file_photos_secondaire"][0]["image_miniature"][0]) ? $produit["path_file_photos_secondaire"][0]["image_miniature"][0] : "";
	    $image_secondaire["path_file_photos_secondaire"][0]["image_miniature"][1] = !empty($produit["path_file_photos_secondaire"][0]["image_miniature"][1]) ? $produit["path_file_photos_secondaire"][0]["image_miniature"][1] : "";
	    $image_secondaire["path_file_photos_secondaire"][0]["image_miniature"][2] = !empty($produit["path_file_photos_secondaire"][0]["image_miniature"][2]) ? $produit["path_file_photos_secondaire"][0]["image_miniature"][2] : "";
	    $image_secondaire["path_file_photos_secondaire"][1]["image"] = !empty($produit["path_file_photos_secondaire"][1]["image"]) ? $produit["path_file_photos_secondaire"][1]["image"] : "";
	    $image_secondaire["path_file_photos_secondaire"][1]["image_miniature"][0] = !empty($produit["path_file_photos_secondaire"][1]["image_miniature"][0]) ? $produit["path_file_photos_secondaire"][1]["image_miniature"][0] : "";
	    $image_secondaire["path_file_photos_secondaire"][1]["image_miniature"][1] = !empty($produit["path_file_photos_secondaire"][1]["image_miniature"][1]) ? $produit["path_file_photos_secondaire"][1]["image_miniature"][1] : "";
	    $image_secondaire["path_file_photos_secondaire"][1]["image_miniature"][2] = !empty($produit["path_file_photos_secondaire"][1]["image_miniature"][2]) ? $produit["path_file_photos_secondaire"][1]["image_miniature"][2] : "";

    	return "Produit::firstOrCreate([
    	    'nom' => \"". $produit["nom"] ."\",
		    'description' => \"". $produit["description"] ."\",
		    'famille_id' => ". $produit["famille_id"] .",
		    'categorie_id' => ". $produit["categorie_id"] .",
		    'type_id' => ". $produit["type_id"] .",
		    'marque_id' => ". $produit["marque_id"] .",
		    'poids' => ". $produit["poids"] .",
		    'status' => \"" . $produit["status"] ."\",
		    'path_file_photo_principale' => 
		    [
		        '0' => 
		        [
		            'image' => \"". $produit["path_file_photo_principale"][0]["image"] ."\",
		            'image_miniature' => 
		                [
		                    '0' => \"". $produit["path_file_photo_principale"][0]["image_miniature"][0] ."\",
		                    '1' => \"". $produit["path_file_photo_principale"][0]["image_miniature"][1] ."\",
		                    '2' => \"". $produit["path_file_photo_principale"][0]["image_miniature"][2] ."\",
		                ]
		        ]
		    ],
		    'path_file_photos_secondaire' => 
		    [
		        '0' => 
		        [
		            'image' => \"". $image_secondaire["path_file_photos_secondaire"][0]["image"] ."\",
		            'image_miniature' => 
		                [
		                    '0' => \"". $image_secondaire["path_file_photos_secondaire"][0]["image_miniature"][0] ."\",
		                    '0' => \"". $image_secondaire["path_file_photos_secondaire"][0]["image_miniature"][1] ."\",
		                    '0' => \"". $image_secondaire["path_file_photos_secondaire"][0]["image_miniature"][2] ."\",
		                ]
		        ],
		        '1' => 
		        [
		            'image' => \"". $image_secondaire["path_file_photos_secondaire"][1]["image"] ."\",
		            'image_miniature' => 
		                [
		                    '0' => \"". $image_secondaire["path_file_photos_secondaire"][1]["image_miniature"][0] ."\",
		                    '0' => \"". $image_secondaire["path_file_photos_secondaire"][1]["image_miniature"][1] ."\",
		                    '0' => \"". $image_secondaire["path_file_photos_secondaire"][1]["image_miniature"][2] ."\",
		                ]
		        ]
		    ],
		    'longueur' => ". $produit["longueur"] .",
		    'largeur' => ". $produit["largeur"] .",
		    'hauteur' => ". $produit["hauteur"] .",
		    'volume' => ". $produit["volume"] .",
		    'ref_produit' => \"". $produit["ref_produit"] ."\",
		    'unite_mesure' => \"". $produit["unite_mesure"] ."\",
    	]);";
    }

	/**
	 * Creation d'un template de requete de creation d'un marque.
	 * @param $marque_nom
	 * @return string
	 */
    public function templateMarques($marque_nom)
    {
		return "MarqueProduit::firstOrCreate(['nom' => \"$marque_nom\"]);";
    }

	/**
	 * Suppression d'une image sur le serveur (peut servire en cas de remplacement pour eviter de stocker des images
	 * non utilisées)
	 * @param $file_path
	 */
    public function deleteImageFile($file_path)
    {
		if (($exists = Storage::disk('public')->exists($file_path)))
		    Storage::disk('public')->delete($file_path);
    }

	/**
	 * Upload d'une nouvelle image sur le serveur en retournant le chemin public pour pouvoir y acceder depuis le site
	 * @param $datas_file
	 * @param $file_name
	 * @param $dest_forlder
	 * @param $image_sizes
	 * @return string
	 * @throws \Exception
	 */
	public function uploadNewImageFile($datas_file, $file_name, $dest_forlder, $image_sizes)
	{
		$images_produits_path = public_path('/storage/bobby_images/enseigne/');
		$file_name = str_replace(" ", "_", $file_name);
		//Verification de l'encodage de l'image
		$parse_result = $this->parseImageBase64($datas_file, $file_name, $dest_forlder);

		//Upload
		if (!Storage::disk('public')->put($parse_result["full_path"], $parse_result["data"]))
			abort(400, "Can't upload file.");

		Image::make($images_produits_path.$file_name.".".$parse_result["type"])
			->resize($image_sizes["larg"], $image_sizes["long"])
			->save($images_produits_path.$file_name.".".$parse_result["type"]);

		return asset("storage/".$parse_result["full_path"]);
	}
}
