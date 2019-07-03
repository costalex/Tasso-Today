<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class apiRequestCreateEntrepriseUserTest extends TestCase
{
//	use RefreshDatabase;
	/**
	 * Test des retours d'erreures de la creation d'un compte
	 * @test
	 * @runInSeparateProcess
	 */
	public function apiRequestCreateEntrepriseUserParametersVerification()
	{
		$response = $this->post('/api/createUser', [
			'email' => 'test@unit.com', 'password' => '123456',
			'nom_enseigne' => 'test'
		]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: type activite ne doit pas etre vide.",
						1 => "Le champ: type entreprise ne doit pas etre vide.",
						2 => "Le champ: addresse contact entreprise ne doit pas etre vide.",
						3 => "Le champ: code postal contact entreprise ne doit pas etre vide.",
						4 => "Le champ: commune contact entreprise ne doit pas etre vide.",
						5 => "Le champ: addresse fact contact entreprise ne doit pas etre vide.",
						6 => "Le champ: code postal fact entreprise ne doit pas etre vide.",
						7 => "Le champ: commune fact entreprise ne doit pas etre vide.",
						8 => "Le champ: email fact contact entreprise ne doit pas etre vide.",
						9 => "Le champ: nom contact ne doit pas etre vide.",
						10 => "Le champ: prenom contact ne doit pas etre vide.",
						11 => "Le champ: telephone contact ne doit pas etre vide.",
						12 => "Le champ: email contact ne doit pas etre vide.",
						13 => "Le champ: siret ne doit pas etre vide.",
						14 => "Le champ: ville ne doit pas etre vide."
					]
				]);
		$response = $this->post('/api/createUser', [
			'email' => 'test@unit.com', 'password' => '123456',
			'nom_enseigne' => 1,
			'addresse_contact_entreprise' => 2,
			'code_postal_contact_entreprise' => 3,
			'addresse_fact_contact_entreprise' => 4,
			'code_postal_fact_entreprise' => 5,
		]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: type activite ne doit pas etre vide.",
						1 => "Le champ: type entreprise ne doit pas etre vide.",
						2 => "Le champ: nom enseigne n'a pas le bon format.",
						3 => "Le champ: addresse contact entreprise n'a pas le bon format.",
						4 => "Le champ: code postal contact entreprise n'a pas le bon format.",
						5 => "Le champ: commune contact entreprise ne doit pas etre vide.",
						6 => "Le champ: addresse fact contact entreprise n'a pas le bon format.",
						7 => "Le champ: code postal fact entreprise n'a pas le bon format.",
						8 => "Le champ: commune fact entreprise ne doit pas etre vide.",
						9 => "Le champ: email fact contact entreprise ne doit pas etre vide.",
						10 => "Le champ: nom contact ne doit pas etre vide.",
						11 => "Le champ: prenom contact ne doit pas etre vide.",
						12 => "Le champ: telephone contact ne doit pas etre vide.",
						13 => "Le champ: email contact ne doit pas etre vide.",
						14 => "Le champ: siret ne doit pas etre vide.",
						15 => "Le champ: ville ne doit pas etre vide."

					]
				]);
	}
}
