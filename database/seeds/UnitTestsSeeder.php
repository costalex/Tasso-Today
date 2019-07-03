<?php

use App\Abonnement;
use App\CategorieProduit;
use App\Client;
use App\Contact;
use App\Couleur;
use App\Demande;
use App\Entreprise;
use App\Event;
use App\FamilleProduit;
use App\FondEcran;
use App\Groupe;
use App\MarqueProduit;
use App\Produit;
use App\TypeActivite;
use App\TypeEntreprise;
use App\TypeProduit;
use App\User;
use App\UserType;
use App\Ville;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;


class UnitTestsSeeder extends Seeder
{
	public function run()
	{
		$familles = $this->loadFamillesProduits();
		foreach ($familles as $i => $famille)
		{
			FamilleProduit::firstOrCreate([
				'nom' => $famille['nom'],
				'%_commission' => $famille['%_commission'],
				'img_path' => $famille['img_path']
			]);
			$GLOBALS['famille_produits_id'][$famille['nom']] = $i+1;
		}

		$categories = $this->loadCategoriesProduits();
		foreach ($categories as $i => $categorie)
		{
			CategorieProduit::firstOrCreate([
				'nom' => $categorie['nom'],
				'tailles' => $categorie['taille'],
				'%_commission' => $categorie['%_commission'],
				'dependances_familles_produits' => $categorie['dependance']
			]);
			$GLOBALS['categorie_produits_id'][$categorie['nom']] = $i+1;
		}

		$types = $this->loadTypesProduits();
		foreach ($types as $type)
		{
			TypeProduit::firstOrCreate([
				'nom' => $type['nom'],
				'%_commission' => $type['%_commission'],
				'dependances_categories_produits' => $type['dependance']
			]);
		}

		$marques = $this->loadMarquesProduits();
		foreach ($marques as $marque)
		{
			MarqueProduit::firstOrCreate([
				'nom' => mb_strtoupper($marque['nom'], 'UTF-8')
			]);
		}

		$abonnements = $this->loadAbonnements();
		foreach ($abonnements as $abonnement)
		{
			Abonnement::firstOrCreate([
				'nom' => $abonnement['nom'],
				'nb_max_etageres' => $abonnement['nb_max_etageres'],
				'nb_max_rayons' => $abonnement['nb_max_rayons'],
				'nb_max_sous_rayons' => $abonnement['nb_max_sous_rayons'],
				'nb_max_produits' => $abonnement['nb_max_produits'],
				'nb_max_tete_de_gondole' => $abonnement['nb_max_tete_de_gondole'],
				'nb_max_code_promo' => $abonnement['nb_max_code_promo'],
				'%_commission' => $abonnement['%_commission'],
				'%_reduction_commission' => $abonnement['%_reduction_commission'],
				'prix' => $abonnement['prix']
			]);
		}

		$couleurs = $this->loadCouleurs();
		foreach ($couleurs as $couleur)
		{
			Couleur::firstOrCreate([
				'nom' => $couleur['nom'],
				'code_hexa' => $couleur['code_hexa']
			]);
		}

		$types_entreprises = $this->loadTypesEntreprises();
		foreach ($types_entreprises as $types_entreprise)
		{
			TypeEntreprise::firstOrCreate([
				'abreviation' => $types_entreprise['abreviation'],
				'nom' => $types_entreprise['nom']
			]);
		}

		$events = $this->loadEvents();
		foreach ($events as $event)
		{
			Event::firstOrCreate(['nom' => $event]);
		}

		$fonds_ecran = $this->loadFondsEcran();
		foreach ($fonds_ecran as $fond_ecran)
		{
			FondEcran::firstOrCreate([
				'event_id' => $fond_ecran['event_id'],
				'label' => $fond_ecran['label'],
				'path_file_image' => $fond_ecran['path_file_image'],
				'positions_produits' => $fond_ecran['positions_produits'],
				'prix' => $fond_ecran['prix']
			]);
		}

		$user_types = $this->loadUserTypes();
		foreach ($user_types as $user_type)
		{
			UserType::firstOrCreate(['nom' => $user_type['nom']]);
		}

		$groupes_users = $this->loadGroupesUsers();
		foreach ($groupes_users as $groupe_user)
		{
			Groupe::firstOrCreate([
				'label' => $groupe_user['label'],
				'code_groupe' => $groupe_user['code_groupe']
			]);
		}

		Artisan::call('passport:install');
		$passport_clients = $this->initPassportClients();
		foreach ($passport_clients as $passport_client) {
			DB::table('oauth_clients')->insert([
				'name' => $passport_client['name'],
				'secret' => $passport_client['secret'],
				'redirect' => $passport_client['redirect'],
				'personal_access_client' => $passport_client['personal_access_client'],
				'password_client' => $passport_client['password_client'],
				'revoked' => $passport_client['revoked']
			]);
		}

		$villes = $this->loadVilles();
		foreach ($villes as $ville)
		{
			Ville::firstOrCreate(['nom' => $ville]);
		}

		$types_activites = $this->loadTypesActivites();
		foreach ($types_activites as $types_activite)
		{
			TypeActivite::firstOrCreate(['nom' => $types_activite]);
		}

		$this->loadTestEnvironnement();
	}

	/**
	 * Initialisation des types d'activites
	 */
	public function loadTypesActivites()
	{
		$types_activites = [];

		array_push($types_activites, "Bijouterie");
		array_push($types_activites, "Boulangerie");
		array_push($types_activites, "Boulangerie patisserie");
		array_push($types_activites, "Boucherie");
		array_push($types_activites, "Boucherie halal");
		array_push($types_activites, "Boucherie casher");
		array_push($types_activites, "Brasserie");
		array_push($types_activites, "Bricolage");
		array_push($types_activites, "Bébé puériculture");
		array_push($types_activites, "Chocolaterie");
		array_push($types_activites, "Charcuterie");
		array_push($types_activites, "Caviste");
		array_push($types_activites, "Culture");
		array_push($types_activites, "Décoration");
		array_push($types_activites, "Épicerie fine");
		array_push($types_activites, "Épicerie italienne");
		array_push($types_activites, "Épicerie asiatique");
		array_push($types_activites, "Épicerie sucrée");
		array_push($types_activites, "Équipement bureau");
		array_push($types_activites, "Équipement cuisine");
		array_push($types_activites, "Équipement maison");
		array_push($types_activites, "Électronique informatique");
		array_push($types_activites, "Fleuriste");
		array_push($types_activites, "Fromager");
		array_push($types_activites, "Horlogerie");
		array_push($types_activites, "Hygiène et beauté");
		array_push($types_activites, "Lingerie");
		array_push($types_activites, "Librairie");
		array_push($types_activites, "Modes");
		array_push($types_activites, "Modes homme");
		array_push($types_activites, "Modes femme");
		array_push($types_activites, "Négociant");
		array_push($types_activites, "Primeur");
		array_push($types_activites, "Parfumerie");
		array_push($types_activites, "Poissonnerie");
		array_push($types_activites, "Pharmacie parapharmacie");
		array_push($types_activites, "Patisserie");
		array_push($types_activites, "Quincaillerie");
		array_push($types_activites, "Sport");
		array_push($types_activites, "Torréfacteur");

		return $types_activites;
	}

	/**
	 * Initialisation des villes
	 */
	public function loadVilles()
	{
		$villes = [];

		array_push($villes, "Bordeaux");

		return $villes;
	}

	/**
	 * Initialisation des marques des produits (Chargement des marques de base)
	 */
	public function loadMarquesProduits()
	{
		$marques_list = [];

		array_push($marques_list, ['nom' => 'COCA COLA']);
		array_push($marques_list, ['nom' => 'IBM']);
		array_push($marques_list, ['nom' => 'MICROSOFT']);
		array_push($marques_list, ['nom' => 'GOOGLE']);
		array_push($marques_list, ['nom' => 'GENERAL ELECTRICS']);
		array_push($marques_list, ['nom' => 'MC DONALDS']);
		array_push($marques_list, ['nom' => 'INTEL']);
		array_push($marques_list, ['nom' => 'NOKIA']);
		array_push($marques_list, ['nom' => 'DISNEY']);
		array_push($marques_list, ['nom' => 'HP']);

		return $marques_list;
	}

	/**
	 * Initialisation de Passport avec les codes secret pour la generation des token API
	 */
	public function initPassportClients()
	{
		$passport_clients = [];

		array_push($passport_clients, [
			'name' => "Bobby",
			'secret' => 'qEiKbhbJha2wo1D1vhEyayMcFmB7uinKAubfAkQF',
			'redirect' => "",
			'personal_access_client' => "0",
			'password_client' => "1",
			'revoked' => "0"
		]);

		return $passport_clients;
	}

	/**
	 * Initialisation des Events liées au fond ecran
	 */
	public function loadEvents()
	{
		$evenements = [];

		array_push($evenements, "Pas d'evenement");

		return $evenements;
	}

	/**
	 * Initialisation des familles de produits
	 * A FINIR %commission inconnue pour le moment
	 */
	public function loadFamillesProduits()
	{
		$famille_produits= [];
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Alimentaire', 'commission' => 0.00, 'TVA' => 5.50, 'img' => 'familles_images/Alimentaire.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Animalerie', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/Animalerie.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Auto-Moto', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/Auto-Moto.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Bébé et Puériculture', 'commission' => 0.00, 'TVA' => 20.0, 'img' => 'familles_images/Bébé et Puériculture.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Bureautique', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/Bureautique.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'High-Tech', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/High-Tech.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Hygiène et santé', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/Hygiène et santé.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Jardin', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/Jardin.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Loisirs', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/Loisirs.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Maison', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/Maison.png'],['']));
		array_push($famille_produits, $this->addGroupeProduits(['nom' => 'Mode', 'commission' => 0.00, 'TVA' => 20.00, 'img' => 'familles_images/Mode.png'],['']));

		return $famille_produits;
	}

	/**
	 * Ajout des categories des produits
	 */
	public function loadCategoriesProduits()
	{
		$categories_produits = [];
		$famille_enum = $GLOBALS['famille_produits_id'];

		array_push($categories_produits, array_merge($this->getTailles([6,7]),$this->addGroupeProduits(['nom' => 'Accessoire', 'commission' =>  0.00, 'TVA' => 0.00], $this->createDependance(
			[
				$famille_enum['Auto-Moto'],$famille_enum['Mode'],$famille_enum['Bureautique'],
				$famille_enum['High-Tech'],$famille_enum['Jardin'],$famille_enum['Loisirs'],
				$famille_enum['Animalerie']
			]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Accessoire et Mobilité', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Bébé et Puériculture']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Audiovisuel', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['High-Tech']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Beauté', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Mode']]))));
		array_push($categories_produits, array_merge($this->getTailles([6]), $this->addGroupeProduits(['nom' => 'Boissons', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Alimentaire']]))));
		array_push($categories_produits, array_merge($this->getTailles([7]), $this->addGroupeProduits(['nom' => 'Boulangerie', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Alimentaire']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Bricolage', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Jardin']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Bien-être et Massage', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Hygiène et santé']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Bijoux', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Mode']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Cuisine', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Maison']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Décoration', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance(
			[
				$famille_enum['Bureautique'], $famille_enum['Maison'], $famille_enum['Jardin']
			]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Epicerie', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Alimentaire']]))));
		array_push($categories_produits, array_merge($this->getTailles([5,6,7]), $this->addGroupeProduits(['nom' => 'Erotisme et sensualité', 'commission' => 0.00, 'TVA' => 5.50],$this->createDependance([$famille_enum['Hygiène et santé']]))));
		array_push($categories_produits, array_merge($this->getTailles([7]), $this->addGroupeProduits(['nom' => 'Fruits', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Alimentaire']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'GPS', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['High-Tech']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Hygiène et santé', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Bébé et Puériculture'], $famille_enum['Animalerie']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Hygiène', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Hygiène et santé']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Informatique', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['High-Tech']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Jeux et Jouets', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Loisirs']]))));
		array_push($categories_produits, array_merge($this->getTailles([7]), $this->addGroupeProduits(['nom' => 'Légumes', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Alimentaire']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Luminaires et Eclairages', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Maison'], $famille_enum['Jardin']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Livres', 'commission' => 0.00, 'TVA' => 5.50],$this->createDependance([$famille_enum['Loisirs']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Musique', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Loisirs']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Nourriture', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Bébé et Puériculture'], $famille_enum['Animalerie']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Nettoyage et entretien', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Maison'], $famille_enum['Jardin']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Nutrition et diététique', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Hygiène et santé']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Piece', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Auto-Moto']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Poissons', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Alimentaire']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Photo et Camescope', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['High-Tech']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Petit Électroménager', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Maison']]))));
		array_push($categories_produits, array_merge($this->getTailles([6]), $this->addGroupeProduits(['nom' => 'Rasage et épilation', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Hygiène et santé']]))));
		array_push($categories_produits, array_merge($this->getTailles([6,7]), $this->addGroupeProduits(['nom' => 'Santé et premiers soins', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Hygiène et santé']]))));
		array_push($categories_produits, array_merge($this->getTailles([3,4]), $this->addGroupeProduits(['nom' => 'Sport', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Loisirs']]))));
		array_push($categories_produits, array_merge($this->getTailles([]), $this->addGroupeProduits(['nom' => 'Téléphonie', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['High-Tech']]))));
		array_push($categories_produits, array_merge($this->getTailles([2,3,5]), $this->addGroupeProduits(['nom' => 'Vêtements', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Mode']]))));
		array_push($categories_produits, array_merge($this->getTailles([7]), $this->addGroupeProduits(['nom' => 'Viandes', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$famille_enum['Alimentaire']]))));

		return $categories_produits;
	}

	/**
	 * Recuperation des tailles a proposer au vendeur lors du set de ses stocks en fonction du produit
	 * 1: Chaussures
	 * 2: Casquettes, bonnets, casques
	 * 3: Textiles
	 * 4: GANTS DE BOXE
	 * 5: SOUTIENS GORGE
	 * 6: LIQUIDE
	 * 7: POIDS
	 */
	public function getTailles($ids_taille)
	{
		$chaussures = ["19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48", "49", "50"];
		$casquettes = ["T0", "T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10"];
		$textiles = ["34 - 2XS","36 - XS","38 - S","40 - M","42 - M-L","42 / 44","44 - L","46 - L-XL","46 / 48","48 - XL","50 - XL-2XL","50 / 52","52 - 2XL","54 - 2XL-3XL","56 - 3XL","58 - 3XL-4XL","60 - 4XL","62 - 4XL-5XL","64 - 5XL","3 Mois","6 Mois","6 à 9 Mois","9 Mois","9 à 12 Mois","12 Mois","18 Mois","2 Ans","3 Ans","4 Ans","5 Ans","6 Ans","8 Ans","10 Ans","12 Ans","14 Ans"];
		$gants_de_boxe = ["6 oz","8 oz","10 oz","12 oz","14 oz","16 oz"];
		$soutien_gorge = ["Bonnet AA","Bonnet A","Bonnet B","Bonnet C","Bonnet D","Bonnet E","Bonnet F","Bonnet G"];
		$liquide = ["L", "CL", "ML"];
		$poids = ["KG", "G", "MG"];
		$unite = ["UNITE"];

		$list_taille["taille"] = [0 => $unite];

		foreach ($ids_taille as $id_taille)
		{
			switch ($id_taille)
			{
				case 1: array_push($list_taille["taille"], $chaussures);
				case 2: array_push($list_taille["taille"], $casquettes);
				case 3: array_push($list_taille["taille"], $textiles);
				case 4: array_push($list_taille["taille"], $gants_de_boxe);
				case 5: array_push($list_taille["taille"], $soutien_gorge);
				case 6: array_push($list_taille["taille"], $liquide);
				case 7: array_push($list_taille["taille"], $poids);
				default: return $list_taille;
			}
		}
		return $list_taille;
	}

	/**
	 * Ajout des types de produits
	 */
	public function loadTypesProduits()
	{
		$types_produits = [];
		$categorie_enum = $GLOBALS['categorie_produits_id'];

		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Accessoire', 'commission' => 0.00, 'TVA' => 20.00],$this->createDependance([$categorie_enum['Cuisine']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Alcoolisé', 'commission' => 0.00, 'TVA' => 20.00],$this->createDependance([$categorie_enum['Boissons']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Assaisonnement', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Epicerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Conserve', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance(
			[$categorie_enum['Fruits'],$categorie_enum['Légumes'],$categorie_enum['Epicerie']
			])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Charcuterie', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Viandes']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Crèmerie', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Epicerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Enfant', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Vêtements']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Epice', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Epicerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Farine et céréales', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Epicerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Femme', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([
			$categorie_enum['Bijoux'],$categorie_enum['Vêtements'],$categorie_enum['Beauté']
		])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Frais', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Fruits'],$categorie_enum['Légumes']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Goûter et Confiserie', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Epicerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Homme', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([
			$categorie_enum['Bijoux'],$categorie_enum['Vêtements'],$categorie_enum['Beauté']
		])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Hygiène dentaire', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Hygiène']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Hygiène corporelle', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Hygiène']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Instrument', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Musique']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Non Alcoolisé', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Boissons']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Ordinateur portable', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Informatique']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Pain', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Boulangerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Pâtes', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Epicerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Patisserie', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Boulangerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Petit déjeuner', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Epicerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Plat préparé', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Epicerie']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Poissons maigre', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Poissons']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Poissons mi-gras', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Poissons']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Poissons gras', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Poissons']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Poissons surgelé', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Poissons']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Sac et bagage', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Sport']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Sec', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Fruits']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Son', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Audiovisuel']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Tablette', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Informatique']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'TV', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Audiovisuel']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Vetements femme', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Sport']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Vetements homme', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Sport']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Viandes Rouge', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Viandes']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Viandes Blanche', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Viandes']])));
		array_push($types_produits, $this->addGroupeProduits(['nom' => 'Vienoiserie', 'commission' => 0.00, 'TVA' => 0.00],$this->createDependance([$categorie_enum['Boulangerie']])));

		return $types_produits;
	}

	/**
	 * Ajout des abonnements
	 */
	public function loadAbonnements()
	{
		$abonnements = [];

		array_push($abonnements, [
			'nom' => 'STANDARD',
			'nb_max_etageres' => '10',
			'nb_max_rayons' => '10',
			'nb_max_sous_rayons' => '10',
			'nb_max_produits' => '12',
			'nb_max_tete_de_gondole' => '0',
			'nb_max_code_promo' => '0',
			'%_commission' => '25.00',
			'%_reduction_commission' => '0.00',
			'prix' => '0.00'
		]);
		array_push($abonnements, [
			'nom' => 'MEDIUM',
			'nb_max_etageres' => '4',
			'nb_max_rayons' => '4',
			'nb_max_sous_rayons' => '4',
			'nb_max_produits' => '48',
			'nb_max_tete_de_gondole' => '4',
			'nb_max_code_promo' => '4',
			'%_commission' => '15.00',
			'%_reduction_commission' => '0.00',
			'prix' => '39.00'
		]);
		//Premium a redefinir
		array_push($abonnements, [
			'nom' => 'PREMIUM',
			'nb_max_etageres' => '4',
			'nb_max_rayons' => '4',
			'nb_max_sous_rayons' => '4',
			'nb_max_produits' => '48',
			'nb_max_tete_de_gondole' => '4',
			'nb_max_code_promo' => '10',
			'%_commission' => '13.00',
			'%_reduction_commission' => '0.00',
			'prix' => '59.00'
		]);

		return $abonnements;
	}

	/**
	 * Ajout des couleurs avec leurs code hexa
	 */
	public function loadCouleurs()
	{
		$couleurs = [];

		//Default
		array_push($couleurs, ['nom' => 'Aucune', 'code_hexa' => '']);

		//NUANCES ROUGE
		array_push($couleurs, ['nom' => 'Blanc', 'code_hexa' => '#ffffff']);
		array_push($couleurs, ['nom' => 'Lonestar', 'code_hexa' => '#660000']);
		array_push($couleurs, ['nom' => 'Baie rouge', 'code_hexa' => '#990000']);
		array_push($couleurs, ['nom' => 'Garde rouge', 'code_hexa' => '#CC0000']);
		array_push($couleurs, ['nom' => 'Rouge', 'code_hexa' => '#FF0000']);
		array_push($couleurs, ['nom' => 'Doux-amer', 'code_hexa' => '#FF6666']);
		array_push($couleurs, ['nom' => 'Mona Lisa', 'code_hexa' => '#FF9999']);
		array_push($couleurs, ['nom' => 'Your Pink', 'code_hexa' => '#FFCCCC']);

		//NUANCES ORANGE
		array_push($couleurs, ['nom' => 'Noir', 'code_hexa' => '#000000']);
		array_push($couleurs, ['nom' => 'Bois de noix de muscade', 'code_hexa' => '#663300']);
		array_push($couleurs, ['nom' => 'Oregon', 'code_hexa' => '#993300']);
		array_push($couleurs, ['nom' => 'Blaze Orange', 'code_hexa' => '#FF6600']);
		array_push($couleurs, ['nom' => 'Orange outrancière', 'code_hexa' => '#FF6633']);
		array_push($couleurs, ['nom' => 'Mandarine atomique', 'code_hexa' => '#FF9966']);
		array_push($couleurs, ['nom' => 'Orange pêche', 'code_hexa' => '#FFCC99']);

		//NUANCES JAUNE
		array_push($couleurs, ['nom' => 'Mine Shaft', 'code_hexa' => '#333333']);
		array_push($couleurs, ['nom' => 'Potters Clay', 'code_hexa' => '#996633']);
		array_push($couleurs, ['nom' => 'Bouddha Or', 'code_hexa' => '#CC9900']);
		array_push($couleurs, ['nom' => 'Supernova', 'code_hexa' => '#FFCC00']);
		array_push($couleurs, ['nom' => 'Jaune', 'code_hexa' => '#FFFF00']);
		array_push($couleurs, ['nom' => 'Pâle Canaries', 'code_hexa' => '#FFFF99']);
		array_push($couleurs, ['nom' => 'Crème', 'code_hexa' => '#FFFFCC']);

		//NUANCES VERT
		array_push($couleurs, ['nom' => 'Dove Gray', 'code_hexa' => '#666666']);
		array_push($couleurs, ['nom' => 'Sapin profond', 'code_hexa' => '#003300']);
		array_push($couleurs, ['nom' => 'Camarone', 'code_hexa' => '#006600']);
		array_push($couleurs, ['nom' => 'Laurel japonais', 'code_hexa' => '#009900']);
		array_push($couleurs, ['nom' => 'Vert', 'code_hexa' => '#00FF00']);
		array_push($couleurs, ['nom' => 'Menthe verte', 'code_hexa' => '#99FF99']);
		array_push($couleurs, ['nom' => 'Menthe enneigée', 'code_hexa' => '#CCFFCC']);

		//NUANCES BLEU-VERT
		array_push($couleurs, ['nom' => 'Gris poussiéreux', 'code_hexa' => '#999999']);
		array_push($couleurs, ['nom' => 'Sarcelle profonde', 'code_hexa' => '#003333']);
		array_push($couleurs, ['nom' => 'Pierre bleue', 'code_hexa' => '#006666']);
		array_push($couleurs, ['nom' => 'Vert persan', 'code_hexa' => '#009999']);
		array_push($couleurs, ['nom' => 'Downy', 'code_hexa' => '#66CCCC']);
		array_push($couleurs, ['nom' => 'Bleu vert', 'code_hexa' => '#66FFCC']);

		//NUANCES BLEU-CLAIR
		array_push($couleurs, ['nom' => 'Gris', 'code_hexa' => '#CCCCCC']);
		array_push($couleurs, ['nom' => 'Ruban bleu', 'code_hexa' => '#0066FF']);
		array_push($couleurs, ['nom' => 'Azure Radiance', 'code_hexa' => '#0099FF']);
		array_push($couleurs, ['nom' => 'Cyan / Aqua', 'code_hexa' => '#00FFFF']);
		array_push($couleurs, ['nom' => 'Anakiwa', 'code_hexa' => '#99FFFF']);

		//NUANCES BLEU-FONCE
		array_push($couleurs, ['nom' => 'Bleu marin', 'code_hexa' => '#000066']);
		array_push($couleurs, ['nom' => 'Bleu foncé', 'code_hexa' => '#0000CC']);
		array_push($couleurs, ['nom' => 'Bleu', 'code_hexa' => '#0000FF']);
		array_push($couleurs, ['nom' => 'Malibu', 'code_hexa' => '#66CCFF']);
		array_push($couleurs, ['nom' => 'Anakiwa', 'code_hexa' => '#99CCFF']);

		//NUANCES VIOLET
		array_push($couleurs, ['nom' => 'Christalle', 'code_hexa' => '#330066']);
		array_push($couleurs, ['nom' => 'Violet électrique', 'code_hexa' => '#9900CC']);
		array_push($couleurs, ['nom' => 'Violet foncé', 'code_hexa' => '#9933FF']);
		array_push($couleurs, ['nom' => 'Melrose', 'code_hexa' => '#9999FF']);
		array_push($couleurs, ['nom' => 'Pervenche', 'code_hexa' => '#CCCCFF']);

		//NUANCES ROSE
		array_push($couleurs, ['nom' => 'Kimberly', 'code_hexa' => '#666699']);
		array_push($couleurs, ['nom' => 'Pompadour', 'code_hexa' => '#660066']);
		array_push($couleurs, ['nom' => 'Fresh Eggplant', 'code_hexa' => '#990066']);
		array_push($couleurs, ['nom' => 'Hollywood Cerise', 'code_hexa' => '#CC0099']);
		array_push($couleurs, ['nom' => 'Hollywood Cerise Clair', 'code_hexa' => '#FF0099']);
		array_push($couleurs, ['nom' => 'Magenta / Fuchsia', 'code_hexa' => '#FF00FF']);
		array_push($couleurs, ['nom' => 'Lavande Rose', 'code_hexa' => '#FF99FF']);
		array_push($couleurs, ['nom' => 'Dentelle rose', 'code_hexa' => '#FFCCFF']);

		return $couleurs;
	}

	/**
	 * Ajout des types d'entreprise
	 */
	public function loadTypesEntreprises()
	{
		$types_entreprises = [];

		array_push($types_entreprises, $this->addTypeEntreprise('EARL','Entreprise agricole à responsabilité limitée'));
		array_push($types_entreprises, $this->addTypeEntreprise('EI','Entreprise individuelle'));
		array_push($types_entreprises, $this->addTypeEntreprise('EIRL','Entreprise individuelle à responsabilité limitée'));
		array_push($types_entreprises, $this->addTypeEntreprise('EURL','Entreprise unipersonnelle à responsabilité limitée'));
		array_push($types_entreprises, $this->addTypeEntreprise('GAEC','Groupement agricole d\'exploitation en commun'));
		array_push($types_entreprises, $this->addTypeEntreprise('GEIE','Groupement européen d\'intérêt économique'));
		array_push($types_entreprises, $this->addTypeEntreprise('GIE','Groupement d\'intérêt économique'));
		array_push($types_entreprises, $this->addTypeEntreprise('SARL','Société à responsabilité limitée'));
		array_push($types_entreprises, $this->addTypeEntreprise('SA','Société anonyme'));
		array_push($types_entreprises, $this->addTypeEntreprise('SAS','Société par actions simplifiée'));
		array_push($types_entreprises, $this->addTypeEntreprise('SASU','Société par actions simplifiée unipersonnelle'));
		array_push($types_entreprises, $this->addTypeEntreprise('SC','Société civile'));
		array_push($types_entreprises, $this->addTypeEntreprise('SCA','Société en commandite par actions'));
		array_push($types_entreprises, $this->addTypeEntreprise('SCI','Société civile immobilière'));
		array_push($types_entreprises, $this->addTypeEntreprise('SCIC','Société coopérative d\'intérêt collectif'));
		array_push($types_entreprises, $this->addTypeEntreprise('SCM','Société civile de moyens'));
		array_push($types_entreprises, $this->addTypeEntreprise('SCOP','Société coopérative ouvrière de production'));
		array_push($types_entreprises, $this->addTypeEntreprise('SCP','Société civile professionnelle'));
		array_push($types_entreprises, $this->addTypeEntreprise('SCS','Société en commandite simple'));
		array_push($types_entreprises, $this->addTypeEntreprise('SEL','Société d\'exercice libéral'));
		array_push($types_entreprises, $this->addTypeEntreprise('SELAFA','Société d\'exercice libéral à forme anonyme'));
		array_push($types_entreprises, $this->addTypeEntreprise('SELARL','Société d\'exercice libéral à responsabilité limitée'));
		array_push($types_entreprises, $this->addTypeEntreprise('SELAS','Société d\'exercice libéral par actions simplifiée'));
		array_push($types_entreprises, $this->addTypeEntreprise('SELCA','Société d\'exercice libéral en commandite par actions'));
		array_push($types_entreprises, $this->addTypeEntreprise('SEM','Société d\'économie mixte'));
		array_push($types_entreprises, $this->addTypeEntreprise('SEML','Société d\'économie mixte locale'));
		array_push($types_entreprises, $this->addTypeEntreprise('SEP','Société en participation'));
		array_push($types_entreprises, $this->addTypeEntreprise('SICA','Société d\'intérêt collectif agricole'));
		array_push($types_entreprises, $this->addTypeEntreprise('SNC','Société en nom collectif'));

		return $types_entreprises;
	}

	/**
	 * Ajout des fonds ecran
	 */
	protected function loadFondsEcran()
	{
		$fonds_ecran = [];

		$no_event_id = Event::where('nom', "Pas d'evenement")->first();

		//Laisser le fond ecran par defaut avec l'id 1 (set par defaut de fond lors de la creation d'une etagere)
		array_push($fonds_ecran, $this->createFondEcran(
			[
				"event_id" => $no_event_id->id,
				"label" => "default",
				"path_file_image" => asset('/storage/fonds_ecran/default.png'),
				"prix" => 0.00,
				"activated" => true
			],[
			['left' => '4%', 'top' => '8%'],['left' => '30%', 'top' => '8%'],['left' => '56%', 'top' => '8%'],['left' => '82%', 'top' => '8%'],
			['left' => '4%', 'top' => '41%'],['left' => '30%', 'top' => '41%'],['left' => '56%', 'top' => '41%'],['left' => '82%', 'top' => '41%'],
			['left' => '4%', 'top' => '73%'],['left' => '30%', 'top' => '73%'],['left' => '56%', 'top' => '73%'],['left' => '82%', 'top' => '73%'],
		]));

		return $fonds_ecran;
	}

	/**
	 * Ajout des types de compte (user_types)
	 */
	public function loadUserTypes()
	{
		$user_types = [];

		array_push($user_types, ['nom' => 'Admin']);
		array_push($user_types, ['nom' => 'Entreprise']);
		array_push($user_types, ['nom' => 'Client']);

		return $user_types;
	}

	/**
	 * Ajout de groupe utilisateurs
	 */
	public function loadGroupesUsers()
	{
		$groupe_users = [];

		array_push($groupe_users, $this->addGoupeUser("Defaut", "STANDARD"));
		//TESTE
		array_push($groupe_users, $this->addGoupeUser("Defauttest", "STANDARD2"));

		return $groupe_users;
	}

	/**
	 * Outils de creation de groupes utilisateur(s)
	 */
	public function addGoupeUser($groupe_name, $code)
	{
		return [
			'label' => $groupe_name,
			'code_groupe' => $code
		];
	}

	/**
	 * Outils de creation de fond d'ecran
	 */
	public function createFondEcran($params, $positions_produits)
	{
		$fond_ecran = [];
		$positions_produits_tmp = [];

		$fond_ecran['event_id'] = $params['event_id'];
		$fond_ecran['label'] = $params['label'];
		$fond_ecran['path_file_image'] = $params['path_file_image'];
		$fond_ecran['prix'] = $params['prix'];
		$fond_ecran['activated'] = empty($params['prix']) ? false : $params['prix'];

		foreach ($positions_produits as $i => $pos_produit)
		{
			$positions_produits_tmp[$i] = $pos_produit;
		}
		$fond_ecran['positions_produits'] = $positions_produits_tmp;

		return $fond_ecran;
	}

	/**
	 * Outils de creation de dependance de groupe
	 */
	public function createDependance($dependance_recup)
	{
		$dependance = [];

		foreach ($dependance_recup as $i => $item)
		{
			array_push($dependance, $item);
		}

		return $dependance;
	}

	/**
	 * Outils de creation de familles categories et types de produits
	 */
	public function addGroupeProduits($infos, $dependance)
	{
		return [
			'nom' => $infos['nom'],
			'%_commission' => $infos['commission'],
			'TVA' => $infos['TVA'],
			'img_path' => !empty($infos['img']) ? asset("/storage/bobby_images/" . $infos['img']) : "",
			'dependance' => $dependance
		];
	}

	/**
	 * Outils de creation de types d entreprise
	 */
	public function addTypeEntreprise($abreviation, $nom)
	{
		return [
			'abreviation' => $abreviation,
			'nom' => $nom
		];
	}

	/**
	 * Generation de données afin de pouvoir tester l'application en dur en attendant la
	 * mise en production.
	 */
	public function loadTestEnvironnement()
	{
		/**
		 * Admin-1, test de status appliqué par defaut
		 */
		User::firstOrCreate([
			'user_type_id' => '1',
			'email' => 'testadmin@dev.com',
			'password' => Hash::make('test'),
			'status' => 'ACTIVE'
		]);

		/**
		 * ENTREPRISE-1, test de status ACTIVE OUVERT
		 */
		$this->createTestEntreprises([
			'user_email' => 'ent1@dev.com',
			'user_type_id' => '2',
			'user_status' => 'ACTIVE',
			'entreprise_status' => 'OUVERT',

			'nom_enseigne' => 'Entreprise teste 1',
			'addresse_contact_entreprise' => '9 Rue Pierlot',
			'addresse_fact_contact_entreprise' => '9 Rue Pierlot',
			'code_postal_contact_entreprise' => '33000',
			'commune_contact_entreprise' => 'Merignac1',
			'email_fact_contact_entreprise' => 'testfact1@mail.com',
			'code_postal_fact_entreprise' => '33100',
			'commune_fact_entreprise' => 'Merignac11',

			'nom_contact' => 'test_nom_contact11',
			'prenom_contact' => 'test_prenom_contact11',
			'telephone_contact' => '0678901025',
			'email_contact' => 'test11@mail.com',

			'description' => 'Description de test1 pour le dev',
			'siret' => '01234567890121'
		]);


		/**
		 * CLIENT-1, test de status VALIDATION_EN_ATTENTE
		 */
		Client::FirstOrCreate([
			'user_id' => User::firstOrCreate([
				'user_type_id' => '3',
				'email' => 'client1@dev.com',
				'password' => Hash::make('test'),
				'status' => 'ACTIVE'
			])->id,
			'list_groupe_id' =>  [1],
			'nom' => 'nom_dev1',
			'prenom' => 'prenom_dev1',
			'addresse_facturation' => Contact::create(
				[
					'nom' => 'nom_dev1',
					'prenom' => 'prenom_dev1',
					'telephone' => '0666666666',
					'addresse_fact' => '13 avenue du teste1',
					'code_postal_fact' => '33000',
					'ville_fact' => 'Bordeaux'
				])['id'],
			'addresses_livraison' => [Contact::create(
				[
					'nom' => 'nom_dev1',
					'prenom' => 'prenom_dev1',
					'telephone' => '0666666666',
					'addresse' => '10 Rue Bouffard',
					'code_postal' => '33000',
					'ville' => 'Bordeaux'
				])['id']
			],
			'telephone' => '0666666666'
		]);

		$unite[0] = "KG";
		$unite[1] = "L";
		$unite[2] = "UNITE";

		for ($i = 1; $i <= 36; ++$i)
		{
			$status = 1;
			if ($i == 31 || $i == 32 || $i == 33 || $i == 34 || $i == 35 || $i == 36)
				$status = 0;

			$this->createTestProduits([
				'nom' => "Prod $i",
				'description' => str_random(150),
				'famille_id' => $i > 11 ? $i/10 : $i,
				'categorie_id' => $i,
				'type_id' => rand(1,37),
				'marque_id' => rand(1,10),
				'entreprise_id' => $status == 0 ? 2 : 0,
				'poids' => rand(10,500),
				'status' => $status == 0 ? "PRIVE" : "PUBLIC",
				'path_file_photo_principale' =>
					[
						'0' =>
							[
								'image' => "http://www.berarddistribution.fr/wp-content/themes/berarddistribution/img/icon/icon-cart.png",
								'image_miniature' =>
									[
										'0' => "https://secure.gravatar.com/avatar/c4e840123342c6c4b7d745ba72b83d42?s=40&d=wavatar&r=g",
										'1' => "http://www.cedrom-sni.com/app/assets/media/generated/iconproduits_01_pages_product_icon.png?1407184037",
										'2' => "https://www.wibre.de/files/public/icons/iconcertified.png",
									]
							]
					],
				'path_file_photos_secondaire' =>
					[
						'0' =>
							[
								'image' => "http://lichtenheldt.de/wp-content/uploads/2016/12/icon_logistik.png",
								'image_miniature' =>
									[
										'0' => "http://1.gravatar.com/avatar/af6c6f5f911d519757caa99efa7fcca8?s=40&d=wavatar&r=g",
									]
							],
						'1' =>
							[
								'image' => "https://upload.wikimedia.org/wikipedia/commons/thumb/1/10/Circle-icons-tractor.svg/200px-Circle-icons-tractor.svg.png",
								'image_miniature' =>
									[
										'0' => "https://images-na.ssl-images-amazon.com/images/I/41EME37AZfL._SS40_.jpg",
									]
							]
					],
				'longueur' => rand(10,50),
				'largeur' => rand(10,50),
				'hauteur' => rand(10,50),
				'ref_produit' => str_random(16),
				'unite_mesure' => $unite[rand(0,2)],
			]);
		}

//		$types_demande = ['CREATION', 'SUPPRESSION', 'BUG', 'PRODUITS', 'PAIEMENT', 'RDV', 'NON_DEFINI'];

//		for ($i = 0; $i < 7; $i++)
//		{
//			Demande::firstOrCreate([
//				'entreprise_id' => '1',
//				'ville_id' => '1',
//				'type_activite_id' => '12',
//				'type' => $types_demande[$i],
//				'details' => str_random(60)
//			]);
//		}
//
//		for ($i = 0; $i < 7; $i++)
//		{
//			Demande::firstOrCreate([
//				'entreprise_id' => '2',
//				'ville_id' => '1',
//				'type_activite_id' => '27',
//				'type' => $types_demande[$i],
//				'details' => str_random(60)
//			]);
//		}
//
//		for ($i = 0; $i < 7; $i++)
//		{
//			Demande::firstOrCreate([
//				'entreprise_id' => '3',
//				'ville_id' => '1',
//				'type_activite_id' => '7',
//				'type' => $types_demande[$i],
//				'details' => str_random(60)
//			]);
//		}
	}

	/**
	 * Outil de creation de la list de produits pour la phase de test
	 * @param $produit_datas
	 */
	public function createTestProduits($produit_datas)
	{
		$url_random_image = "https://picsum.photos/";
		$random_image = "/?image=";
		$rand_principale = rand(1,800);
		$rand_secondaire_1 = rand(1,800);
		$rand_secondaire_2 = rand(1,800);

		$produit = Produit::firstOrCreate([
			'nom' => $produit_datas["nom"],
			'description' => $produit_datas["description"],
			'famille_id' => $produit_datas["famille_id"],
			'categorie_id' => $produit_datas["categorie_id"],
			'type_id' => $produit_datas["type_id"],
			'marque_id' => $produit_datas["marque_id"],
			'entreprise_id' => $produit_datas["entreprise_id"],
			'poids' => $produit_datas["poids"],
			'status' => $produit_datas["status"],
			'path_file_photo_principale' =>
				[
					'0' =>
						[
							'image' => $url_random_image.'640'.$random_image.$rand_principale,
							'image_miniature' =>
								[
									'0' => $url_random_image.'40'.$random_image.$rand_principale,
									'1' => $url_random_image.'100'.$random_image.$rand_principale,
									'2' => $url_random_image.'200'.$random_image.$rand_principale,
								]
						]
				],
			'path_file_photos_secondaire' =>
				[
					'0' =>
						[
							'image' => $url_random_image.'640'.$random_image.$rand_secondaire_1,
							'image_miniature' =>
								[
									'0' => $url_random_image.'40'.$random_image.$rand_secondaire_1,
									'1' => $url_random_image.'100'.$random_image.$rand_secondaire_1,
									'2' => $url_random_image.'200'.$random_image.$rand_secondaire_1,
								]
						],
					'1' =>
						[
							'image' => $url_random_image.'640'.$random_image.$rand_secondaire_2,
							'image_miniature' =>
								[
									'0' => $url_random_image.'40'.$random_image.$rand_secondaire_2,
									'1' => $url_random_image.'100'.$random_image.$rand_secondaire_2,
									'2' => $url_random_image.'200'.$random_image.$rand_secondaire_2,
								]
						]
				],
			'longueur' => $produit_datas["longueur"],
			'largeur' => $produit_datas["largeur"],
			'hauteur' => $produit_datas["hauteur"],
			'volume' => $produit_datas["longueur"] * $produit_datas["largeur"] * $produit_datas["hauteur"],
			'ref_produit' => str_random(6),
			'unite_mesure' => $produit_datas["unite_mesure"],
		]);

		if (($taille_id = strlen((string)$produit->id)) < 6)
			$produit->ref_produit = $produit->id . mb_strtoupper(str_random(6-$taille_id), 'UTF-8');
		else
			$produit->ref_produit = $produit->id . mb_strtoupper(str_random(1), 'UTF-8');
		$produit->save();
	}

	/**
	 * Outil de creation de la list des entreprises pour la phase de test
	 * @param $entreprise_datas
	 */
	public function createTestEntreprises($entreprise_datas)
	{
		Entreprise::firstOrCreate(
			[
				'user_id' => User::firstOrCreate([
					'user_type_id' => $entreprise_datas['user_type_id'],
					'email' => $entreprise_datas['user_email'],
					'password' => Hash::make('test'),
					'password_caisse' => Hash::make(str_random(6)),
					'status' => $entreprise_datas['user_status']
				])->id,
				'status' => $entreprise_datas['entreprise_status'],
				'abonnement_id' => (Abonnement::where(['nom' => 'STANDARD'])->first())->id,
				'type_activite_id' => rand(1,28),
				'type_entreprise_id' => rand(1,29),
				'nom_enseigne' => $entreprise_datas['nom_enseigne'],
				'addresse_entreprise_contact_id' => Contact::firstOrCreate([
					'nom' => $entreprise_datas['nom_enseigne'],
					'addresse' => $entreprise_datas['addresse_contact_entreprise'],
					'code_postal' => $entreprise_datas['code_postal_contact_entreprise'],
					'commune' => $entreprise_datas['commune_contact_entreprise'],
					'ville' => 'Bordeaux',
					'addresse_fact' => $entreprise_datas['addresse_fact_contact_entreprise'],
					'code_postal_fact' => $entreprise_datas['code_postal_fact_entreprise'],
					'commune_fact' => $entreprise_datas['commune_fact_entreprise'],
					'email_fact' => $entreprise_datas['email_fact_contact_entreprise']
				])->id,
				'contact_entreprise_id' => Contact::firstOrCreate([
					'nom' => $entreprise_datas['nom_contact'],
					'prenom' => $entreprise_datas['prenom_contact'],
					'telephone' => $entreprise_datas['telephone_contact'],
					'email' => $entreprise_datas['email_contact']
				])->id,
				'description' => $entreprise_datas['description'],
				'siret' => $entreprise_datas['siret'],
				'Coordonnées_GPS' => [
					'1' => '180',
					'2' => '215',
					'3' => '289'
				],
				'ville_id' => 1,
				'horraires_ouverture' => [
					'L' => '9:00;12:00;14:00;17:00',
					'Ma' => '9:00;12:00;14:00;17:00',
					'Me' => '9:00;12:00;1400;17:00',
					'J' => '9:00;12:00;14:00;17:00',
					'V' => '9:00;12:00;14:00;17:00',
					'S' => '9:00;12:00;14:00;17:00',
					'D' => '9:00;12:00;14:00;17:00',
				],
				'banniere' => asset('/storage/bobby_images/enseigne/default-banner.svg'),
				'path_file_logo_entreprise' => asset('/storage/bobby_images/enseigne/default.svg'),
				'liste_produits' => array(),
				'shop_order' => array(),
				'facture_commissions' => array(),
				'fonds' => (FondEcran::where('label', 'default')->first())->id,
				'taille_lots' => array(),
				'reseaux_sociaux' => [
					"facebook" => "",
					"instagram" => "",
					"twitter" => "",
					"pinterest" => ""
				]
			]);
	}
}
