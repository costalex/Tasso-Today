<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;
use App\Entreprise;
use App\Etagere;
use function json_decode;
use function json_encode;
use App\Rayon;
use App\SousRayon;
use App\User;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	/**
	 * Unit Test Fonction permettant l'appel de controller en personalisant les argumens, les methodes ainsi que la route uri d'acces
	 * @param $funct
	 * @param $uri
	 * @param $method
	 * @param $args
	 * @return mixed
	 */
	public function uTControllerCall($funct, $uri, $method, $args)
	{
		try
		{
			return Response(call_user_func_array($funct, [Request::create($uri, $method, $args)]), 200);
		}
		catch (\Exception $exception)
		{
			echo  $exception->getMessage();
			if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
				return Response(['message' => $exception->getMessage()], 404);
			else if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException)
				return Response(['message' => $exception->getMessage()], $exception->getStatusCode());
			else if ($exception instanceof \Stripe\Error\Base || $exception instanceof \Stripe\Error\ApiConnection   ||
				$exception instanceof \Stripe\Error\Authentication || $exception instanceof \Stripe\Error\InvalidRequest  ||
				$exception instanceof \Stripe\Error\RateLimit || $exception instanceof \Stripe\Error\Card)
				return Response(['message' => $exception->getMessage()], 400);
		}

		return Response(['message' => 'Empty response'], 500);
	}

	/**
	 * UNIT TEST FORCE CONTEXT
	 */

	/**
	 * Ajout d'un stock fictif aux produits de l'entrprise de test par defaut
	 */
	public function AddStockProduitsEntreprise()
	{
		$produits_entreprise = Entreprise::where(['id' => 1])->first()->liste_produits;
		foreach ($produits_entreprise as $i => $produit)
		{
			$produits_entreprise[$i]['stocks'] = [
				0 => [
					"id" => 0,
					"model" => "Piece",
					"couleur" => [
						'nom' => "Aucune",
						'code_hexa' => ""
					],
					"prix" => 5,
					"activer" => true,
					"longueur" => 1,
					"largeur" => 2,
					"hauteur" => 3,
					"volume" => 6,
					"poids" => 5,
					"stock" => 20,
					"afficher" => true
				]
			];
		}
		Entreprise::where(['id' => 1])->update(['liste_produits' => json_encode($produits_entreprise)]);
	}

	/**
	 * Creation d'un rayon sous-rayon et etagere dans laquelle on met tous les produits de l'entrprise de test par defaut
	 */
	public function createRayonAndAddAllProduits()
	{
		$user = User::where(['email' => 'ent1@dev.com'])->first();
		$ent = Entreprise::where(['user_id' => $user->id])->first();
		Auth::login($user);

		$etagere_produits[1] = json_decode('[{"position": {"top": "8%", "left": "4%"}, "id_produit": 1, "id_position": 0}, {"position": {"top": "8%", "left": "30%"}, "id_produit": 2, "id_position": 1}, {"position": {"top": "8%", "left": "56%"}, "id_produit": 3, "id_position": 2}, {"position": {"top": "8%", "left": "82%"}, "id_produit": 4, "id_position": 3}, {"position": {"top": "41%", "left": "4%"}, "id_produit": 5, "id_position": 4}, {"position": {"top": "41%", "left": "30%"}, "id_produit": 6, "id_position": 5}, {"position": {"top": "41%", "left": "56%"}, "id_produit": 7, "id_position": 6}, {"position": {"top": "41%", "left": "82%"}, "id_produit": 8, "id_position": 7}, {"position": {"top": "73%", "left": "4%"}, "id_produit": 9, "id_position": 8}, {"position": {"top": "73%", "left": "30%"}, "id_produit": 10, "id_position": 9}, {"position": {"top": "73%", "left": "56%"}, "id_produit": 11, "id_position": 10}, {"position": {"top": "73%", "left": "82%"}, "id_produit": 12, "id_position": 11}]');
		$etagere_produits[2] = json_decode('[{"position": {"top": "8%", "left": "4%"}, "id_produit": 13, "id_position": 0}, {"position": {"top": "8%", "left": "30%"}, "id_produit": 14, "id_position": 1}, {"position": {"top": "8%", "left": "56%"}, "id_produit": 15, "id_position": 2}, {"position": {"top": "8%", "left": "82%"}, "id_position": 3}, {"position": {"top": "41%", "left": "4%"}, "id_position": 4}, {"position": {"top": "41%", "left": "30%"}, "id_position": 5}, {"position": {"top": "41%", "left": "56%"}, "id_position": 6}, {"position": {"top": "41%", "left": "82%"}, "id_position": 7}, {"position": {"top": "73%", "left": "4%"}, "id_position": 8}, {"position": {"top": "73%", "left": "30%"}, "id_position": 9}, {"position": {"top": "73%", "left": "56%"}, "id_position": 10}, {"position": {"top": "73%", "left": "82%"}, "id_position": 11}]');
		$rayon_id = Rayon::create([
			'nom' => 'ut_Rayon',
			'entreprise_id' => $ent->id
		])['id'];
		$sous_rayon_id = SousRayon::create([
			'nom' => 'ut_SousRayon',
			'entreprise_id' => $ent->id,
			'rayon_id' => $rayon_id
		])['id'];
		for ($i = 1; $i <= 2; ++$i)
		{
			Etagere::create([
				'nom' => 'ut_Etagere',
				'entreprise_id' => $ent->id,
				'rayon_id' => $rayon_id,
				'sous_rayon_id' => $sous_rayon_id,
				'list_produits' => $etagere_produits[$i]
			])['id'];
		}
		$ent->shop_order = json_decode('{"A0": {"A00": {"A000": {"etagere_id": 1, "etagere_nom": "ut_Etagere"}, "A001": {"etagere_id": 2, "etagere_nom": "ut_Etagere"}, "sous_rayon_id": 1, "sous_rayon_nom": "ut_SousRayon"}, "rayon_id": 1, "rayon_nom": "ut_Rayon"}}');
		$ent->save();
	}
}
