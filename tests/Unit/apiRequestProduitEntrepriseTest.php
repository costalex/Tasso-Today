<?php

namespace Tests\Unit;

use App\Entreprise;
use App\Http\Controllers\EntrepriseInformationsController;
use App\Produit;
use App\User;
use Illuminate\Support\Facades\Auth;
use function sizeof;
use Tests\TestCase;

class apiRequestProduitEntrepriseTest extends TestCase
{
    /**
     * Teste d'ajout et de suppression de produits d'une entreprise
     * @runInSeparateProcess
     * @return void
     */
    public function testAddProduits()
    {
	    $user = User::where(['email' => 'ent1@dev.com'])->first();
	    Auth::login($user);

    	for($i = 1; $i <= 15; ++$i)
	    {
		    $response = $this->uTControllerCall(array(new EntrepriseInformationsController(), 'addProduitsEntreprise'),
			    "/api/addProduitsEntreprise",
			    'POST',
			    [
				    'id_entreprise' => 1,
				    'id_produit' => (Produit::where(['id' => $i])->first())->id
			    ]);
		    $this->assertEquals(200, $response->status());
			$this->assertEquals($i, sizeof(app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseProduitsList()));
	    }
    }

	/**
	 * Teste de la suppression de produits d'une entreprise
     * @runInSeparateProcess
	 */
	public function testDeleteProduits()
	{
		$this->testAddProduits();
		for($i = 1; $i <= 5; ++$i)
		{
			$response = $this->uTControllerCall(array(new EntrepriseInformationsController(), 'deleteProduitsEntreprise'),
				"/api/deleteProduitsEntreprise",
				'POST',
				[
					'id_entreprise' => 1,
					'id_produit' => (Produit::where(['id' => $i])->first())->id
				]);
			$this->assertEquals(200, $response->status());
			$this->assertEquals(15-$i, sizeof(app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseProduitsList()));
		}
		$this->assertEquals(10, sizeof(app('App\Http\Controllers\EntrepriseInformationsController')->getEntrepriseProduitsList()));
	}

	/**
	 * Teste du nombres de produits trouver dans les etageres de chaques entreprise ainsi que du nombre de produit proposés
	 * dans chaque famille (permet de retrouver les entreprises proposant des produits d'une certaine famille en vente)
	 * @runInSeparateProcess
	 */
	public function testGetEntreprisesFamilles()
	{
		$this->testAddProduits();
		$this->AddStockProduitsEntreprise();
		$this->createRayonAndAddAllProduits();

		$famille_count[1] = 4;
		$famille_count[2] = 2;
		$famille_count[3] = 1;
		$famille_count[4] = 1;
		$famille_count[5] = 1;
		$famille_count[6] = 1;
		$famille_count[7] = 1;
		$famille_count[8] = 1;
		$famille_count[9] = 1;
		$famille_count[10] = 1;
		$famille_count[11] = 1;

		$user = User::where(['email' => 'ent1@dev.com'])->first();
		$ent = Entreprise::where(['user_id' => $user->id])->first();
		Auth::login($user);

		$response = $this->withHeaders(['API-TOKEN' => $user->api_token])
			->json('GET', '/api/getEntreprisesFamilles/-1');
		$this->assertEquals(200, $response->status());

		for($i = 1; $i <= 11; ++$i)
		{
			$this->assertEquals(1, sizeof($response->getOriginalContent()[$i]['shops']));
			$this->assertEquals($famille_count[$i], $response->getOriginalContent()[$i]['shops'][$ent->id]['count']);
		}
	}

//	public function testGetEntreprisesCategories()
//	{
//		$this->testAddProduits();
//		$this->AddStockProduitsEntreprise();
//		$this->createRayonAndAddAllProduits();
//
//		$famille_count[1] = 4;
//		$famille_count[2] = 2;
//		$famille_count[3] = 1;
//		$famille_count[4] = 1;
//		$famille_count[5] = 1;
//		$famille_count[6] = 1;
//		$famille_count[7] = 1;
//		$famille_count[8] = 1;
//		$famille_count[9] = 1;
//		$famille_count[10] = 1;
//		$famille_count[11] = 1;
//
//		$user = User::where(['email' => 'ent1@dev.com'])->first();
//		$ent = Entreprise::where(['user_id' => $user->id])->first();
//		Auth::login($user);
//
//		for($i = 1; $i <= 11; ++$i)
//		{
//			$response = $this->withHeaders(['API-TOKEN' => $user->api_token])
//				->json('GET', '/api/getEntreprisesCategories/');
//			$this->assertEquals(200, $response->status());
//			$this->assertEquals(1, sizeof($response->getOriginalContent()[$i]['shops']));
//			$this->assertEquals($famille_count[$i], $response->getOriginalContent()[$i]['shops'][$ent->id]['count']);
//		}
//	}
}
