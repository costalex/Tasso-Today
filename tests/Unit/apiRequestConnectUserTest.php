<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class apiRequestConnectUserTest extends TestCase
{
//	use RefreshDatabase;
	/**
     * Tests de la connection user, entreprise et admin
	 * @runInSeparateProcess
     * @return void
     */
    public function testConnection()
    {
	    User::where(['email' => 'client1@dev.com'])->update(['status' => 'VALIDATION_EN_ATTENTE']);
	    $response = $this->post('/api/login', [
	    	'email' => 'client1@dev.com',
	    	'password' => 'test'
		    ]);
	    $response
		    ->assertStatus(400)
		    ->assertJson(
			    [
				    "message" => "Compte inactif ou invalide."
			    ]);
	    User::where(['email' => 'ent1@dev.com'])->update(['status' => 'VALIDATION_EN_ATTENTE']);
	    $response = $this->post('/api/login', [
		    'email' => 'ent1@dev.com',
		    'password' => 'test'
	    ]);
	    $response
		    ->assertStatus(400)
		    ->assertJson(
		    [
			    "message" => "Compte inactif ou invalide."
		    ]);
	    User::where(['email' => 'ent1@dev.com'])->update(['status' => 'ACTIVATION_EN_ATTENTE']);
	    $response = $this->post('/api/login', [
		    'email' => 'ent1@dev.com',
		    'password' => 'test'
	    ]);
	    $response
		    ->assertStatus(400)
		    ->assertJson(
			    [
				    "message" => "Compte inactif ou invalide."
			    ]);
	    $response = $this->post('/api/login', [
		    'email' => 'testadmin@dev.com',
		    'password' => 'test'
	    ]);

	    $response
		    ->assertStatus(200)
		    ->assertJson(
			    [
				    "message" => (User::select('email', 'api_token')
					    ->where(['email' => 'testadmin@dev.com'])
				        ->first())->api_token
			    ]);
	    User::where(['email' => 'client1@dev.com'])->update(['status' => 'ACTIVE']);

	    $response = $this->post('/api/login', [
		    'email' => 'client1@dev.com',
		    'password' => 'test'
	    ]);
	    $response
		    ->assertStatus(200)
		    ->assertJson(
			    [
				    "message" => (User::select('email', 'api_token')
					    ->where(['email' => 'client1@dev.com'])
					    ->first())->api_token
			    ]);
	    User::where(['email' => 'ent1@dev.com'])->update(['status' => 'ACTIVE']);

	    $response = $this->post('/api/login', [
		    'email' => 'ent1@dev.com',
		    'password' => 'test'
	    ]);
	    $response
		    ->assertStatus(200)
		    ->assertJson(
			    [
				    "message" => (User::select('email', 'api_token')
					    ->where(['email' => 'ent1@dev.com'])
					    ->first())->api_token
			    ]);
    }
}
