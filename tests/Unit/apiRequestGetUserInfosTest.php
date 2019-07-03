<?php

namespace Tests\Unit;

use App\Client;
use App\Entreprise;
use App\Http\Controllers\ClientInformationsController;
use App\Http\Controllers\UserInformationsController;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class apiRequestGetUserInfosTest extends TestCase
{
//	use RefreshDatabase;

	/**
	 * Test sur les methodes GET, PUT et DELETE de userInfos
	 * @runInSeparateProcess
	 */
	public function testUserInfos()
	{
		$UserInfoController = new UserInformationsController();
		$response = $this->post('/api/login', [
			'email' => 'client1@dev.com',
			'password' => 'test'
		]);
		$user = User::where(['email' => 'client1@dev.com'])->first();
		Auth::login($user);
		$save_token = $user->api_token;

		$response
			->assertStatus(200)
			->assertJson(
				[
					"message" => $save_token
				]);
		$response = $this->withHeaders(['API-TOKEN' => $save_token,])
			->json('GET', '/api/userInfos/0');
		$response->assertStatus(200);

		$response = $this->uTControllerCall(array(new ClientInformationsController(), 'updateClient'),
			"/api/userInfos/0",
			'PUT',
			[
				'nom' => 'test',
				'prenom' => 'unitaire',
				'telephone' => '123456',
				'addresse' => '1 rue du test',
				'code_postal' => '33000123',
				'ville' => 'test'
			]);
		$this->assertEquals(200, $response->status());
		$this->assertEquals(false, empty($client = Client::where(['user_id' => $user->id])->with('contacts_facturation')->first()));
		$this->assertEquals('test', $client->nom);
		$this->assertEquals('unitaire', $client->prenom);
		$this->assertEquals('123456', $client->telephone);
		$this->assertEquals('1 rue du test', $client->contacts_facturation->addresse_fact);
		$this->assertEquals('33000123', $client->contacts_facturation->code_postal_fact);
		$this->assertEquals('test', $client->contacts_facturation->ville_fact);

		$response = $this->withHeaders(['API-TOKEN' => $save_token,])
			->json('DELETE', '/api/userInfos/0');
		$response->assertStatus(200);

		$response = $this->uTControllerCall(array($UserInfoController, 'userInfosUpdate'),
			"/api/userInfosUpdate",
			'PUT',
			[
				'email' => 'test@unitaire.com',
				'password' => '12346',
				'password_caisse' => '7890',
			]);
		$this->assertEquals(204, $response->status());
		$this->assertEquals(false, empty($user = User::where(['email' => 'test@unitaire.com'])->first()));
		$this->assertEquals('test@unitaire.com', $user->email);
		$this->assertEquals(true, Hash::check('12346', $user->password));
		$this->assertEquals(true, Hash::check('7890', $user->password_caisse));
	}

	/**
	 * Test d'un changement de statut d'un user par un admin
	 * @runInSeparateProcess
	 */
	public function testUserStatut()
	{
		$UserInfoController = new UserInformationsController();
		$user = User::where(['email' => 'ent1@dev.com'])->first();
		$ent = Entreprise::where(['user_id' => $user->id])->first();
		$response = $this->uTControllerCall(array($UserInfoController, 'userStatusUpdate'),
			"/api/userStatusUpdate",
			'PUT',
			[
				'id' => $ent->id,
				'status' => 'BAN'
			]);
		$this->assertEquals(204, $response->status());
		$user = User::where(['email' => 'ent1@dev.com'])->first();
		$ent = Entreprise::where(['user_id' => $user->id])->first();
		$this->assertEquals('BAN', $user->status);
		$this->assertEquals('ARRET', $ent->status);
		$response = $this->uTControllerCall(array($UserInfoController, 'userStatusUpdate'),
			"/api/userStatusUpdate",
			'PUT',
			[
				'id' => $ent->id,
				'status' => 'ACTIVE'
			]);
		$this->assertEquals(204, $response->status());
		$user = User::where(['email' => 'ent1@dev.com'])->first();
		$ent = Entreprise::where(['user_id' => $user->id])->first();
		$this->assertEquals('ACTIVE', $user->status);
		$this->assertEquals('FERME', $ent->status);
	}
}
