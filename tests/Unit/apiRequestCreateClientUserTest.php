<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class apiRequestCreateClientUserTest extends TestCase
{
//	use RefreshDatabase;
	/**
	 * Test des retours d'erreures de l'entrée de mail lors de la creation d'un compte
	 * @test
	 * @runInSeparateProcess
	 * @return void
	 */
	public function apiRequestCreateClientUserMailVerification()
	{
		$response = $this->post('/api/createUser', ['email' => '']);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: email ne doit pas etre vide.",
						1 => "Le champ: password ne doit pas etre vide."
					]
				]);

		$response = $this->post('/api/createUser', ['email' => 123456]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: email n'a pas le bon format.",
						1 => "Le format de: email est incorrect.",
						2 => "Le champ: password ne doit pas etre vide."
					]
				]);
		$response = $this->post('/api/createUser', ['email' => '0123456789012345678901234567890123456789012345678901234567890123456789']);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "La champ: email est trop long.",
						1 => "Le format de: email est incorrect.",
						2 => "Le champ: password ne doit pas etre vide."
					]
				]);
		$response = $this->post('/api/createUser', ['email' => '123456']);
		$response->assertStatus(400);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le format de: email est incorrect.",
						1 => "Le champ: password ne doit pas etre vide."
					]
				]);
		$response = $this->post('/api/createUser', ['email' => 'client1@dev.com']);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: email existe deja, il doit etre unique.",
						1 => "Le champ: password ne doit pas etre vide."
					]
				]);
		$response = $this->post('/api/createUser', ['email' => 'test@funct.com']);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: password ne doit pas etre vide."
					]
				]);
	}

	/**
	 * Test des retours d'erreures de l'entrée de password lors de la creation d'un compte
	 * @test
	 * @runInSeparateProcess
	 */
	public function apiRequestCreateClientUserPasswordVerification()
	{
		$response = $this->post('/api/createUser', ['password' => '']);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: email ne doit pas etre vide.",
						1 => "Le champ: password ne doit pas etre vide."
					]
				]);
		$response = $this->post('/api/createUser', ['password' => 123456]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: email ne doit pas etre vide.",
						1 => "Le champ: password n'a pas le bon format."
					]
				]);
	}

	/**
	 * Test des retours d'erreures de l'entrée de nom et/ou prenom lors de la creation d'un compte
	 * @test
	 * @runInSeparateProcess
	 */
	public function apiRequestCreateClientUserNomPrenomVerification()
	{
		$response = $this->post('/api/createUser', [
			'email' => 'test@unit.com', 'password' => '123456',
			'firstname' => '',
			'lastname' => '',
			'phone' => ''
		]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: firstname ne doit pas etre vide.",
						1 => "Le champ: lastname ne doit pas etre vide.",
						2 => "Le champ: phone ne doit pas etre vide."
					]
				]);
		$response = $this->post('/api/createUser', [
			'email' => 'test@unit.com', 'password' => '123456',
			'firstname' => '(test)',
			'lastname' => '',
			'phone' => ''
		]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Caractere(s) incorrect dans votre nom ou prenom."
					]
				]);
		$response = $this->post('/api/createUser', [
			'email' => 'test@unit.com', 'password' => '123456',
			'firstname' => '',
			'lastname' => '(test)',
			'phone' => ''
		]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Caractere(s) incorrect dans votre nom ou prenom."
					]
				]);
		$response = $this->post('/api/createUser', [
			'email' => 'test@unit.com', 'password' => '123456',
			'firstname' => 'test',
			'lastname' => 'unit',
			'phone' => '01234567890123456789'
		]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "La champ: phone est trop long."
					]
				]);
		$response = $this->post('/api/createUser', [
			'email' => 'test@unit.com', 'password' => '123456',
			'firstname' => 'test',
			'lastname' => 'unit',
			'phone' => 123
		]);
		$response
			->assertStatus(400)
			->assertJson(
				[
					"message" => [
						0 => "Le champ: phone n'a pas le bon format."
					]
				]);
	}
}
