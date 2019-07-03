<?php

namespace App\Http\Controllers;

use function abort;
use App\Client;
use App\Groupe;
use App\Entreprise;
use App\Mail\ConfirmMail;
use App\Mail\PasswordReset;
use App\User;
use App\UserType;
use function array_add;
use function array_push;
use function asset;
use Carbon\Carbon;
use function config;
use function getenv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function json_decode;
use function json_encode;
use Laravel\Socialite\Facades\Socialite;
use function preg_match;
use function redirect;
use function response;
use function str_random;
use GuzzleHttp\Client as guzzleClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use function url;
use function var_dump;
use function view;

class UserInformationsController extends Controller
{
	/**
	 * CONNECTION CLASSIQUE
	 */

	/**
	 * Creation d'un compte de maniere traditionnel
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$errors_messages = [
			'required' => 'Le champ: :attribute ne doit pas etre vide.',
			'email' => 'Le format de: :attribute est incorrect.',
			'string' => "Le champ: :attribute n'a pas le bon format.",
			'max' => 'La champ: :attribute est trop long.',
			'unique' => 'Le champ: :attribute existe deja, il doit etre unique.',
			'alpha' => 'Le champ: :attribute ne doit avoir que des lettres alphabétiques.',
			'regex' => 'Le champ: :attribute contient des caractères interdits.'
		];
		try{
			Validator::make($request->all(), [
				'email' => 'required|string|max:50|email|unique:users',
				'password' => 'required|string|'
			],$errors_messages)->validate();
		} catch (ValidationException $e)
		{
			return response()->json(['message' => empty($e->validator) ? [$e->getMessage()] : $e->validator->getMessageBag()->all()], 400);
		}

		if (!Auth::check())
		{
			if (!isset($request->nom_enseigne))
				$user_type = UserType::where('nom', "Client")->first();
			elseif (isset($request->nom_enseigne))
				$user_type = UserType::where('nom', "Entreprise")->first();
		}
		else
			$user_type = UserType::where(['nom' => Auth::user()->userType->nom])->first();

		if ($user_type->nom === "Client")
		{
			try
			{
				if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+]/', $request->firstname) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+]/', $request->lastname))
					abort(400,  "Caractere(s) incorrect dans votre nom ou prenom.");

				Validator::make($request->all(),
					[
						'firstname' => "required",
						'lastname' => "required",
						'phone' => 'required|string|max:15'
					], $errors_messages)->validate();

				$user = User::firstOrCreate(
					[
						'user_type_id' => $user_type->id,
						'email' => $request->email,
						'password' => Hash::make($request->password)
					]);

				Client::FirstOrCreate([
					'user_id' => $user->id,
					'list_groupe_id' => [1],
					'nom' => $request->lastname,
					'prenom' => $request->firstname,
					'telephone' => $request->phone,
					'addresse_facturation' => 0,
					'addresses_livraison' => [0]
				]);

				$this->verifyMail($user->email, $user->id);
				return response()->json(["message" => "Votre compte a été crée, un mail de confirmation a été envoyé"], 200);
			} catch (\Exception $e)
			{
				return response()->json(['message' => empty($e->validator) ? [$e->getMessage()] : $e->validator->getMessageBag()->all()], 400);
			}
		}
		else if ($user_type->nom === "Entreprise" || $user_type->nom === "Admin")
		{
			try
			{
				if ($user_type->nom === "Entreprise")
				{
					Validator::make($request->all(), [
						'email' => 'required',
						'password' => 'required',
						'type_activite' => 'required',
						'type_entreprise' => 'required',
						'nom_enseigne' => 'required|string|max:50',
						'addresse_contact_entreprise' => 'required|string|max:50',
						'code_postal_contact_entreprise' => 'required|string|max:10',
						'commune_contact_entreprise' => 'required',
						'addresse_fact_contact_entreprise' => 'required|string|max:50',
						'code_postal_fact_entreprise' => 'required|string|max:10',
						'commune_fact_entreprise' => 'required',
						'email_fact_contact_entreprise' => 'required',
						'nom_contact' => 'required',
						'prenom_contact' => 'required',
						'telephone_contact' => 'required',
						'email_contact' => 'required',
						'siret' => 'required',
						'ville' => 'required'
					], $errors_messages)->validate();
				}
				else
				{
					Validator::make($request->all(), [
						'email' => 'required',
						'password' => 'required',
						'type_activite' => 'required',
						'type_entreprise' => 'required',
						'nom_enseigne' => 'required|string|max:50',
						'siret' => 'required',
						'ville' => 'required'
					], $errors_messages)->validate();
					$user_type = UserType::where('nom', "Entreprise")->first();
				}

				$user = User::Create([
					'user_type_id' => $user_type->id,
					'email' => $request->email,
					'password' => Hash::make($request->password),
					'password_caisse' => Hash::make(str_random(6)),
					'status' => 'ACTIVATION_EN_ATTENTE'
				]);

				Entreprise::create(
					[
						'user_id' => $user->id,
						'status' => 'FERME',
						'abonnement_id' => app('App\Http\Controllers\AbonnementController')->getAbonnementId('STANDARD'),
						'type_activite_id' => app('App\Http\Controllers\TypeActiviteController')->getTypeActiviteId($request->type_activite),
						'type_entreprise_id' => app('App\Http\Controllers\TypeEntrepriseController')->getTypeEntrepriseId($request->type_entreprise),
						'nom_enseigne' => $request->nom_enseigne,
						'addresse_entreprise_contact_id' => app('App\Http\Controllers\ContactController')
							->createContact(
								[
									'nom' => !empty($request->nom_fact_entreprise) ? $request->nom_fact_entreprise : $request->nom_enseigne,
									'addresse' => $request->addresse_contact_entreprise,
									'code_postal' => $request->code_postal_contact_entreprise,
									'commune' => $request->commune_contact_entreprise,
									'addresse_fact' => $request->addresse_fact_contact_entreprise,
									'code_postal_fact' => $request->code_postal_fact_entreprise,
									'commune_fact' => $request->commune_fact_entreprise,
									'email_fact' => $request->email_fact_contact_entreprise
								]),
						'contact_entreprise_id' => app('App\Http\Controllers\ContactController')
							->createContact(
								[
									'nom' => $request->nom_contact,
									'prenom' => $request->prenom_contact,
									'telephone' => $request->telephone_contact,
									'email' => $request->email_contact
								]),
						'description' => !empty($request->description) ? $request->description : "",
						'siret' => $request->siret,
						'Coordonnées_GPS' => [
							'1' => '180',
							'2' => '215',
							'3' => '289'
						],
						'ville_id' => app('App\Http\Controllers\VilleController')->getVilleId($request->ville),
						'horraires_ouverture' => [
							'L' => '9:00;12:00;14:00;17:00',
							'Ma' => '9:00;12:00;14:00;17:00',
							'Me' => '9:00;12:00;1400;17:00',
							'J' => '9:00;12:00;14:00;17:00',
							'V' => '9:00;12:00;14:00;17:00',
							'S' => '00:00;00:00;00:00;00:00',
							'D' => '00:00;00:00;00:00;00:00'
						],
						'banniere' => asset('/storage/bobby_images/enseigne/default-banner.svg'),
						'path_file_logo_entreprise' => asset('/storage/bobby_images/enseigne/default.svg'),
						'liste_produits' => array(),
						'shop_order' => array(),
						'facture_commissions' => array(),
						'fonds' => app('App\Http\Controllers\FondEcranController')->getFondEcranId('default'),
						'taille_lots' => array(),
						'reseaux_sociaux' => [
							"facebook" => "",
							"instagram" => "",
							"twitter" => "",
							"pinterest" => ""
						]
					]);

				return response()->json("Votre demande de création de compte a était envoyée, nous vous recontacterons au plus vite.", 200);
			} catch (\Exception $e)
			{
				return response()->json(['message' => empty($e->validator) ? [$e->getMessage()] : $e->validator->getMessageBag()->all()], 400);
			}
		}
	}

	/**
	 * Retourne les informations utilisateur une fois que
	 * l utilisateur s est authentifie et recus sont id
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function connect(Request $request)
	{
		if (!empty($user = User::where('email', $request->email)->with('userType')->first()))
		{
			if ($user->status != 'ACTIVE')
				abort(400, "Compte inactif ou invalide.");

			if (Auth::check())
				Auth::logout();

			if (Hash::check($request->password, $user->password) || $request->api)
			{
				$oauth_client = DB::table('oauth_clients')->select('id', 'secret', 'name')->where('name', "Bobby")->first();
				//recherche d'information d'ancien token genere.
				$oauth_access = DB::table('oauth_access_tokens')->select('id', 'user_id', 'expires_at')->where([
					'user_id' => $user->id,
					'revoked' => 0
				])->first();

				if (empty($oauth_access))
				{
					$http = new guzzleClient();

					try {
						if (!$request->api)
						{
							//Recuperation du token de session par une connection classique.
							$response = $http->post(url('/oauth/token'), [
								'form_params' => [
									'grant_type' => 'password',
									'client_id' => $oauth_client->id,
									'client_secret' => (string)$oauth_client->secret,
									'username' => (string)$user->email,
									'password' => (string)$request->password,
									'scope' => ''
								]
							]);
							$user->refresh_token = json_decode((string)$response->getBody(), true)['refresh_token'];
							$user->api_token = json_decode((string)$response->getBody(), true)['access_token'];
							$user->save();
						}
						else
						{
							$tokenStr = $user->createToken('Api connect')->accessToken;
							$user->api_token = $tokenStr;
							$user->save();
						}
					} catch (ClientException $e) { abort(400, "Creation de votre token d'acces impossible:(" . $e->getResponse()->getBody() . ')'); }
				}
				//Mise a jour du token a la reconnection si token plus valid
				else if (!empty($oauth_access) && Carbon::parse($oauth_access->expires_at)->diff(Carbon::now())->d <= 1)
				{
					$http = new guzzleClient();
					try{
						$response = $http->post(url('/oauth/token'), [
							'form_params' => [
								'grant_type' => 'refresh_token',
								'refresh_token' => (string)$user->refresh_token,
								'client_id' => $oauth_client->id,
								'client_secret' => (string)$oauth_client->secret,
								'scope' => ''
							]
						]);
						$user->refresh_token = json_decode((string)$response->getBody(), true)['refresh_token'];
						$user->api_token = json_decode((string)$response->getBody(), true)['access_token'];
						$user->save();
					} catch (ClientException $e) { abort(400, "Rafraichissement de votre token d'acces impossible"); }
				}
				Cookie::forget('API-TOKEN');
				setcookie('API-TOKEN', $user->api_token);
				Auth::login($user, true);

				abort(200, $user->api_token);
			}
		}
		abort(400, "E-mail ou mot de passe invalide.");
	}

	/**
	 * Recuperation du token généré pour l'admin afin de le lui retourner lors de l'appele a userInfos
	 * @return null
	 */
	public function getAdminToken()
	{
		if (Auth::user()->isAdmin())
			return (User::where(['id' => Auth::user()->id])->first())->api_token;
		return null;
	}

	/**
	 * CONNECTION API EXTERNE
	 */

	/**
	 * Redirection vers le service d'authentification de l'API concernée
	 * @param $provider
	 * @return mixed
	 */
	public function redirectToProvider($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	/**
	 * Appelle du callback de l'api d'authentification exterieur
	 * @param $provider
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function handleProviderCallback($provider, Request $request)
	{
		try{
			$user = Socialite::driver($provider)->user();
		}catch (\Exception  $e)
		{
			if ($provider == "facebook")
			{
				$http = new guzzleClient([
					'base_uri' => 'https://graph.facebook.com',
					'headers' =>
						[
							'Accept' => 'application/json',
							'Content-Type' => 'application/json'
						]
				]);
				try {
					$response = $http->get("/v3.1/oauth/access_token?&client_id=".config("services.facebook.client_id")."&redirect_uri=".config('services.facebook.redirect')."&client_secret=".config("services.facebook.client_secret")."&code=$request->code");
				} catch (ClientException $e) {

					$response = $e->getResponse();
					$responseBodyAsString = $response->getBody()->getContents();
					abort(400, "Echec de l'authentification Facebook: " . $responseBodyAsString);
				}
				$facebook_token = json_decode((string)$response->getBody(), true)["access_token"];
				$user = Socialite::driver($provider)->userFromToken($facebook_token);
			}
		}

		$parsed_infos = $this->parseUserApiInfos($user, $provider);

		$this->connect(new Request([
			'email' => $parsed_infos['email'],
			'password' => $parsed_infos['password'],
			'social_name' => $provider,
			'social_token' => $parsed_infos['social_token'],
			'api' => true
		]));
	}

	/**
	 * Verification de l'existance du compte utilisateur dans la DB
	 * @param $mail_check
	 * @return bool
	 */
	public function socialUserAlreadyExist($mail_check)
	{
		if (!empty(User::where('email', $mail_check)->first()))
			return true;
		return false;
	}

	/**
	 * Demande de traitement des informations de l'api externe et creation si besoin d'un compte utilisateur
	 * @param $user
	 * @param $provider
	 * @return array
	 */
	public function parseUserApiInfos($user, $provider)
	{
		$new_user = null;
		$parsed_infos = $this->getUserInfos($user, $provider);

		if (!$this->socialUserAlreadyExist($parsed_infos['email']))
		{
			//Creation des informations de connection
			$new_user = User::Create([
				'user_type_id' => $parsed_infos['user_type_id'],
				'email' => $parsed_infos['email'],
				'password' => $parsed_infos['password']
			]);
			$new_user->status = $parsed_infos['status'];
			$new_user->save();

			//Creation des informations utilisateur
			Client::Create([
				'user_id' => $new_user->id,
				'list_groupe_id' => $parsed_infos['list_groupe_id'],
				'nom' => $parsed_infos['nom'],
				'prenom' => $parsed_infos['prenom'],
				'addresse_facturation' => 0,
				'addresses_livraison' => [],
				'telephone' => ''
			]);

			if ($parsed_infos['status'] == 'VALIDATION_EN_ATTENTE')
				$this->verifyMail($parsed_infos['email'], $new_user->id);

			//envoyer par mail le clear_password pour que l'utilisateur puisse se connecter en classique
			$password_mail = new PasswordReset();
			$password_mail->setAddressFrom([config('mail.from.address'), config('mail.from.name')]);
			$password_mail->setAddressTo($user->email);
			$password_mail->setNewPassword($parsed_infos['clear_password']);
			Mail::send($password_mail);
		}
		else if (!empty($user = User::where(['email' => $parsed_infos['email']])->first()))
		{
			$parsed_infos['password'] = $user->password;
		}
		return $parsed_infos;
	}

	/**
	 * Deconnection
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function logout(Request $request)
	{
		if (Auth::check())
			Auth::logout();

		Cookie::forget('API-TOKEN');
		$_SESSION = array();
		setcookie('API-TOKEN', NULL, -1);
		abort(200, 'Disconnected');
	}

	/**
	 * CLIENT/ENTREPRISE FONCTIONS COMMUNES
	 */

	/**
	 * Ajout d'un groupe (parsing json)
	 */
	public function addGroupe($client_groups, $code_groupe)
	{
		$list_groups = $client_groups;
		$id_groupe = (Groupe::where('code_groupe', $code_groupe)->first())->id;

		$list_groups = array_add($list_groups, $id_groupe, $id_groupe);

		return $list_groups;
	}

	/**
	 * MAILS
	 */

	/**
	 * Envoie d'un code de confirmation de mail généré par le serveur
	 * @param $mail_user
	 * @param $user_id
	 */
	public function verifyMail($mail_user, $user_id)
	{
		$confirm_mail = new ConfirmMail();
		$confirm_mail->setAddressFrom([config('mail.from.address'), config('mail.from.name')]);
		$confirm_mail->setAddressTo($mail_user);

		//Code de confirmation
		$random_code = str_random(100);
		User::where(['id' => $user_id])->update(['confirm_code' => $random_code]);
		$confirm_mail->setMailCode($random_code);

		Mail::send($confirm_mail);
	}

	/**
	 * Verification du code renvoyé depuis la boite mail de l'utilisateur pour activation du compte
	 * @param $mail_code
	 * @return string
	 */
	public function checkMailCode($mail_code)
	{
		$user = User::where('confirm_code', $mail_code)->first();
		if (empty($user->confirm_code) || $mail_code != $user->confirm_code)
			return "Mauvais code de confirmation de mail";
		else
		{
			User::where('confirm_code', $mail_code)->update([
				'status' => 'ACTIVE',
				'confirm_code' => null
			]);
			return redirect('/login/client')->with([
				'type_user' => 'client',
				'title' => "Authentification.",
				'type' => "website",
				'url'  => getenv('APP_URL')."/login",
				'image' => "https://upload.wikimedia.org/wikipedia/commons/8/86/Phishing_Login.jpg"
			]);
		}
	}

	/**
	 * Generation et envoie d'un nouveau mot de passe
	 * @param Request $request
	 * @return string
	 */
	public function newPassword(Request $request)
	{

		if (!empty($user = User::where('email', $request->email)->first()))
		{
			$new_password = str_random(8);

			$password_mail = new PasswordReset();
			$password_mail->setAddressFrom([config('mail.from.address'), config('mail.from.name')]);
			$password_mail->setAddressTo($user->email);
			$password_mail->setNewPassword($new_password);

			User::where('id', $user->id)->update(['password' => Hash::make($new_password)]);
			Mail::send($password_mail);

			return view('templates.authentication.login')->with([
				'messages' => [
					"Password modifie."
				],
				'messages_type' => 'success',
				'type_user' => 'client',
				'title' => "Authentification.",
				'type' => "website",
				'url'  => getenv('APP_URL')."/login",
				'image' => "https://upload.wikimedia.org/wikipedia/commons/8/86/Phishing_Login.jpg"
			]);
		}
		return view('templates.authentication.login')->with([
			'messages' => [
				"Mail invalid."
			],
			'messages_type' => 'Failure',
			'type_user' => 'client',
			'title' => "Authentification.",
			'type' => "website",
			'url'  => getenv('APP_URL')."/api/login",
			'image' => "https://upload.wikimedia.org/wikipedia/commons/8/86/Phishing_Login.jpg"
		]);
	}

	/**
	 * UPDATE
	 */

	/**
	 * Update informations de connection par un ADMIN
	 */
	public function userStatusUpdate(Request $request)
	{
		if (!empty($request->status) && !empty($entreprise = Entreprise::where('id', $request->id)->first()))
		{
			if($request->status == 'ACTIVE' || $request->status == 'BAN')
			{
				User::where(['id' => $entreprise->user_id])->update(['status' => $request->status]);

				$entreprise->status = $request->status == 'BAN' ? 'ARRET' : 'FERME';
				$entreprise->save();
			}
			else
				User::where(['id' => $entreprise->user_id])->update(['status' => $request->status]);

			abort(204, 'Status mis a jour.');
		}
		abort(400, 'Status manquant ou entreprise inconnue.');
	}

	/**
	 * Mise a jour du compte USER par son proprietaire
	 * @param Request $request
	 */
	public function userInfosUpdate(Request $request)
	{
		if (!empty($target_user = User::where('id', Auth::user()->id)->first()))
		{
			User::where(['id' => Auth::user()->id])
				->when($request->email, function ($query) use ($request) { $query->update(['email' => $request->email]); })
				->when($request->password, function ($query) use ($request) { $query->update(['password' => Hash::make($request->password)]); })
				->when($request->password_caisse, function ($query) use ($request) { $query->update(['password_caisse' => Hash::make($request->password_caisse)]); });
			abort(204, 'Mise a jour effectue.');
		}
		abort(400, 'Une erreure est survenue lors de la mise a jour.');
	}

	/**
	 * SESSION USER
	 */

	/**
	 * Rafraichissement de la session par le token d'authorisation
	 * @param $token
	 */
	public function refreshUserSession()
	{
		if (!Auth::check() && Cookie::has('API-TOKEN'))
		{
			if (!empty($user = User::where(['api_token' => Cookie::get('API-TOKEN')])->first()))
			{
				Auth::login($user,true);
				return true;
			}
			else
				return false;
		}

		return true;
	}

	/**
	 * GETTERS
	 */

	/**
	 * WARNING: A utiliser que pour des appels interne dans des cas tres specifique
	 * @param $user_id
	 * @return mixed
	 */
	public function getUserInstance($user_id)
	{
		return User::where(['id' => $user_id])->first();
	}

	/**
	 * Parsing des informations contenues dans le retour des API externes afin d'uniformiser la recuperation de données
	 * @param $user
	 * @param $provider
	 * @return array
	 */
	public function getUserInfos($user, $provider)
	{
		$parsed_infos = [];
		$random_password = str_random(6);
		//GENERIQUE DATAS
		$parsed_infos['user_type_id'] = (UserType::where('nom', "Client")->first())->id;
		$parsed_infos['password'] = Hash::make($random_password);
		//clear_password - Pour l'envoie par mail suite a la premiere identification
		$parsed_infos['clear_password'] = $random_password;
		$parsed_infos['list_groupe_id'] = [1];
		$parsed_infos['social_token'] = $user->token;
		//GOOGLE, FACEBOOK
		$parsed_infos['prenom'] = $user->nickname;

		switch ($provider)
		{
			case "google": $parsed_infos['email'] = $user->email; $parsed_infos['nom'] = $user->name; $parsed_infos['status'] = $user->user['verified'] ? 'ACTIVE' : 'VALIDATION_EN_ATTENTE'; break;
			case "facebook": $parsed_infos['email'] = $user->user['email']; $parsed_infos['nom'] = $user->user['name']; $parsed_infos['status'] = 'VALIDATION_EN_ATTENTE'; break;
		}

		return $parsed_infos;
	}

	/**
	 * Recuperation des noms de groupe dans le retour des informations utilisateurs
	 */
	public function getGroupesName($groupes_list)
	{
		$groupes_name_list = [];

		foreach ($groupes_list as $i => $id_groupe)
			array_push($groupes_name_list, ["groupe_name_".$i => (Groupe::select('id', 'label')->where('id', $id_groupe)->first())->label]);

		return $groupes_name_list;
	}

	/**
	 * DELETE
	 */

	/**
	 * Softdelete d'un compte utilisateur
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy()
	{
		if (!empty($target_user = User::where('id', Auth::user()->id)->first()) && $this->authorize('proprietary', $target_user))
		{
			if ($target_user->isClient())
			{
				$client = Client::where(['user_id' => $target_user->id])->first();
				app('App\Http\Controllers\UserInformationsController')->deleteContact($client->addresse_facturation);
				foreach ($client->addresses_livraison as $id_contact_livraison)
				{
					app('App\Http\Controllers\UserInformationsController')->deleteContact($id_contact_livraison);
				}
				Client::where(['user_id' => $target_user->id])->delete();
			}
			else if($target_user->isEntreprise())
			{
				$entreprise = Entreprise::where(['user_id' => $target_user->id])->first();
				app('App\Http\Controllers\UserInformationsController')->deleteContact($entreprise->addresse_entreprise_contact_id);
				app('App\Http\Controllers\UserInformationsController')->deleteContact($entreprise->contact_entreprise_id);

				Entreprise::where(['user_id' => $target_user->id])->delete();
			}
			else
				abort(400, "L'utilisateur ne peut pas etre supprimer");
			User::where(['id' => Auth::user()->id])->delete();
			abort(200, "L'utilisateur a etait supprimer");
		}
		else
			abort(400, "L'utilisateur n'existe pas.");
	}
}
