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

$GLOBALS['famille_produits_id'] = [];
$GLOBALS['categorie_produits_id'] = [];

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */

	public function run()
	{
		echo ":---------------------START MIGRATION--------------------:\nInitialisation des familles de produits... :";

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
		echo "         OK \n" . "Initialisation des Categories de produits... :";

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
		echo "         OK \n" . "Initialisation des Types de produits...: ";

		$types = $this->loadTypesProduits();
		foreach ($types as $type)
		{
			TypeProduit::firstOrCreate([
				'nom' => $type['nom'],
				'%_commission' => $type['%_commission'],
				'dependances_categories_produits' => $type['dependance']
			]);
		}
		echo "         OK \n" . "Initialisation des Marques de produits...: ";

		$marques = $this->loadMarquesProduits();
		echo '('.sizeof($marques).' marques)...';
		foreach ($marques as $marque)
		{
			MarqueProduit::firstOrCreate([
				'nom' => mb_strtoupper($marque['nom'], 'UTF-8')
			]);
		}
		echo "         OK \n" . "Initialisation des Abonnements...: ";

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
		echo "         OK \n" . "Initialisation des Couleurs...: ";

		$couleurs = $this->loadCouleurs();
		foreach ($couleurs as $couleur)
		{
			Couleur::firstOrCreate([
				'nom' => $couleur['nom'],
				'code_hexa' => $couleur['code_hexa']
			]);
		}
		echo "         OK \n" . "Initialisation des Types d'entreprises...: ";

		$types_entreprises = $this->loadTypesEntreprises();
		foreach ($types_entreprises as $types_entreprise)
		{
			TypeEntreprise::firstOrCreate([
				'abreviation' => $types_entreprise['abreviation'],
				'nom' => $types_entreprise['nom']
			]);
		}
		echo "         OK \n" . "Initialisation des Evenements...: ";

		$events = $this->loadEvents();
		foreach ($events as $event)
		{
			Event::firstOrCreate(['nom' => $event]);
		}
		echo "         OK \n" . "Initialisation des Fonds ecran...: ";

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
		echo "         OK \n" . "Initialisation des types users...: ";

		$user_types = $this->loadUserTypes();
		foreach ($user_types as $user_type)
		{
			UserType::firstOrCreate(['nom' => $user_type['nom']]);
		}
		echo "         OK \n" . "Initialisation des groupes users...: ";

		$groupes_users = $this->loadGroupesUsers();
		foreach ($groupes_users as $groupe_user)
		{
			Groupe::firstOrCreate([
				'label' => $groupe_user['label'],
				'code_groupe' => $groupe_user['code_groupe']
			]);
		}
		echo "         OK \n" . "Initialisation de Passport users...: ";

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
		echo "         OK \n" . "Initialisation des villes...: ";

		$villes = $this->loadVilles();
		foreach ($villes as $ville)
		{
			Ville::firstOrCreate(['nom' => $ville]);
		}
		echo "         OK \n" . "Initialisation des types d'activites...: ";

		$types_activites = $this->loadTypesActivites();
		foreach ($types_activites as $types_activite)
		{
			TypeActivite::firstOrCreate(['nom' => $types_activite]);
		}
		echo "         OK \n";

//		$this->loadDemoAccount();
		$this->loadTestEnvironnement();
		echo ":---------------------END MIGRATION--------------------:\n";
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
		array_push($marques_list, ['nom' => 'TOYOTA']);
		array_push($marques_list, ['nom' => 'MERCEDES-BENZ']);
		array_push($marques_list, ['nom' => 'GILETTE']);
		array_push($marques_list, ['nom' => 'CISCO']);
		array_push($marques_list, ['nom' => 'BMW']);
		array_push($marques_list, ['nom' => 'LOUIS VUITTON']);
		array_push($marques_list, ['nom' => 'APPLE']);
		array_push($marques_list, ['nom' => 'MARLBORO']);
		array_push($marques_list, ['nom' => 'SAMSUNG']);
		array_push($marques_list, ['nom' => 'HONDA']);
		array_push($marques_list, ['nom' => 'H&M']);
		array_push($marques_list, ['nom' => 'ORACLE']);
		array_push($marques_list, ['nom' => 'PEPSI']);
		array_push($marques_list, ['nom' => 'AMERICAN EXPRESS']);
		array_push($marques_list, ['nom' => 'NIKE']);
		array_push($marques_list, ['nom' => 'SAP']);
		array_push($marques_list, ['nom' => 'NESCAFE']);
		array_push($marques_list, ['nom' => 'IKEA']);
		array_push($marques_list, ['nom' => 'JP MORGAN']);
		array_push($marques_list, ['nom' => 'BUDWEISER']);
		array_push($marques_list, ['nom' => 'UPS']);
		array_push($marques_list, ['nom' => 'HSBC']);
		array_push($marques_list, ['nom' => 'CANON']);
		array_push($marques_list, ['nom' => 'SONY']);
		array_push($marques_list, ['nom' => 'KELLOGG’S']);
		array_push($marques_list, ['nom' => 'AMAZON.COM']);
		array_push($marques_list, ['nom' => 'GOLDMAN SACHS']);
		array_push($marques_list, ['nom' => 'NINTENDO']);
		array_push($marques_list, ['nom' => 'THOMSON REUTERS']);
		array_push($marques_list, ['nom' => 'CITI']);
		array_push($marques_list, ['nom' => 'DELL']);
		array_push($marques_list, ['nom' => 'PHILIPS']);
		array_push($marques_list, ['nom' => 'EBAY']);
		array_push($marques_list, ['nom' => 'GUCCI']);
		array_push($marques_list, ['nom' => 'L’OREAL']);
		array_push($marques_list, ['nom' => 'HEINZ']);
		array_push($marques_list, ['nom' => 'ACCENTURE']);
		array_push($marques_list, ['nom' => 'ZARA']);
		array_push($marques_list, ['nom' => 'SIEMENS']);
		array_push($marques_list, ['nom' => 'FORD']);
		array_push($marques_list, ['nom' => 'COLGATE']);
		array_push($marques_list, ['nom' => 'MORGAN STANLEY']);
		array_push($marques_list, ['nom' => 'WOLKSWAGEN']);
		array_push($marques_list, ['nom' => 'BLACKBERRY']);
		array_push($marques_list, ['nom' => 'MTV']);
		array_push($marques_list, ['nom' => 'AXA']);
		array_push($marques_list, ['nom' => 'NESTLE']);
		array_push($marques_list, ['nom' => 'DANONE']);
		array_push($marques_list, ['nom' => 'XEROX']);
		array_push($marques_list, ['nom' => 'KFC']);
		array_push($marques_list, ['nom' => 'SPRITE']);
		array_push($marques_list, ['nom' => 'ADIDAS']);
		array_push($marques_list, ['nom' => 'AUDI']);
		array_push($marques_list, ['nom' => 'AVON']);
		array_push($marques_list, ['nom' => 'HYUNDAI']);
		array_push($marques_list, ['nom' => 'YAHOO!']);
		array_push($marques_list, ['nom' => 'ALLIANZ']);
		array_push($marques_list, ['nom' => 'SANTANDER']);
		array_push($marques_list, ['nom' => 'HERMES']);
		array_push($marques_list, ['nom' => 'CATERPILLAR']);
		array_push($marques_list, ['nom' => 'KLEENEX']);
		array_push($marques_list, ['nom' => 'PORSCHE']);
		array_push($marques_list, ['nom' => 'PANASONIC']);
		array_push($marques_list, ['nom' => 'BARCLAYS']);
		array_push($marques_list, ['nom' => 'JOHNSON & JOHNSON']);
		array_push($marques_list, ['nom' => 'TIFFANY & CO']);
		array_push($marques_list, ['nom' => 'CARTIER']);
		array_push($marques_list, ['nom' => 'JACK DANIEL’S']);
		array_push($marques_list, ['nom' => 'MOET & CHANDON']);
		array_push($marques_list, ['nom' => 'CREDIT SUISSE']);
		array_push($marques_list, ['nom' => 'SHELL']);
		array_push($marques_list, ['nom' => 'VISA']);
		array_push($marques_list, ['nom' => 'PIZZA HUT']);
		array_push($marques_list, ['nom' => 'GAP']);
		array_push($marques_list, ['nom' => 'CORONA']);
		array_push($marques_list, ['nom' => 'UBS']);
		array_push($marques_list, ['nom' => 'NIVEA']);
		array_push($marques_list, ['nom' => 'ADOBE']);
		array_push($marques_list, ['nom' => 'SMIRNOFF']);
		array_push($marques_list, ['nom' => '3M']);
		array_push($marques_list, ['nom' => 'FERRARI']);
		array_push($marques_list, ['nom' => 'JOHNNIE WALKER']);
		array_push($marques_list, ['nom' => 'HEINEKEN']);
		array_push($marques_list, ['nom' => 'ZURICH']);
		array_push($marques_list, ['nom' => 'ARMANI']);
		array_push($marques_list, ['nom' => 'LANCOME']);
		array_push($marques_list, ['nom' => 'STARBUCKS']);
		array_push($marques_list, ['nom' => 'HARLEY DAVIDSON']);
		array_push($marques_list, ['nom' => 'CAMPBELL’S']);
		array_push($marques_list, ['nom' => 'BURBERRY']);
		array_push($marques_list, ['nom' => 'A Bicyclette']);
		array_push($marques_list, ['nom' => 'Abbaye de Sept-Fons']);
		array_push($marques_list, ['nom' => 'Acapulco']);
		array_push($marques_list, ['nom' => 'Actifry']);
		array_push($marques_list, ['nom' => 'Activia']);
		array_push($marques_list, ['nom' => 'Addict Sport Nutrition']);
		array_push($marques_list, ['nom' => 'Aglina']);
		array_push($marques_list, ['nom' => 'Airwaves']);
		array_push($marques_list, ['nom' => 'Ajinomoto']);
		array_push($marques_list, ['nom' => 'Albert MEnEs']);
		array_push($marques_list, ['nom' => 'All Seasons']);
		array_push($marques_list, ['nom' => 'Allaire']);
		array_push($marques_list, ['nom' => 'Allergo']);
		array_push($marques_list, ['nom' => 'Allos']);
		array_push($marques_list, ['nom' => 'Alnatura']);
		array_push($marques_list, ['nom' => 'Alpen']);
		array_push($marques_list, ['nom' => 'Alpina Savoie']);
		array_push($marques_list, ['nom' => 'Alpro']);
		array_push($marques_list, ['nom' => 'Alsa']);
		array_push($marques_list, ['nom' => 'Alsace Lait']);
		array_push($marques_list, ['nom' => 'Alter Eco']);
		array_push($marques_list, ['nom' => 'Alvalle']);
		array_push($marques_list, ['nom' => 'Ambrosi']);
		array_push($marques_list, ['nom' => 'Amora']);
		array_push($marques_list, ['nom' => "Amy's Kitchen"]);
		array_push($marques_list, ['nom' => 'Anabolic Supplements']);
		array_push($marques_list, ['nom' => 'Ancali']);
		array_push($marques_list, ['nom' => 'Ancel']);
		array_push($marques_list, ['nom' => 'Andechser Natur']);
		array_push($marques_list, ['nom' => 'Andros']);
		array_push($marques_list, ['nom' => 'ANS']);
		array_push($marques_list, ['nom' => 'Antica Pasteria']);
		array_push($marques_list, ['nom' => 'Aoste']);
		array_push($marques_list, ['nom' => 'ApEricube']);
		array_push($marques_list, ['nom' => 'ApErivrais']);
		array_push($marques_list, ['nom' => 'Apollo']);
		array_push($marques_list, ['nom' => 'Aptonia']);
		array_push($marques_list, ['nom' => 'Apurna']);
		array_push($marques_list, ['nom' => 'Aquarius']);
		array_push($marques_list, ['nom' => 'Arabi']);
		array_push($marques_list, ['nom' => 'Ardo']);
		array_push($marques_list, ['nom' => 'Argel']);
		array_push($marques_list, ['nom' => 'AriakE']);
		array_push($marques_list, ['nom' => 'Arizona']);
		array_push($marques_list, ['nom' => 'Armoric']);
		array_push($marques_list, ['nom' => 'Arnaud']);
		array_push($marques_list, ['nom' => "Arnott's"]);
		array_push($marques_list, ['nom' => 'Arok']);
		array_push($marques_list, ['nom' => 'Aroy-D']);
		array_push($marques_list, ['nom' => 'Artesani']);
		array_push($marques_list, ['nom' => 'Artisans du Monde']);
		array_push($marques_list, ['nom' => 'Atkins']);
		array_push($marques_list, ['nom' => 'Autour du Riz']);
		array_push($marques_list, ['nom' => 'Auvernou']);
		array_push($marques_list, ['nom' => 'Avenance']);
		array_push($marques_list, ['nom' => 'Axodiet']);
		array_push($marques_list, ['nom' => 'Ayam']);
		array_push($marques_list, ['nom' => 'Babybel']);
		array_push($marques_list, ['nom' => 'Babybio']);
		array_push($marques_list, ['nom' => 'Badoit']);
		array_push($marques_list, ['nom' => 'Badoz']);
		array_push($marques_list, ['nom' => 'Baff']);
		array_push($marques_list, ['nom' => 'Bahier']);
		array_push($marques_list, ['nom' => 'Bahlsen']);
		array_push($marques_list, ['nom' => 'Baïko']);
		array_push($marques_list, ['nom' => 'Baileys']);
		array_push($marques_list, ['nom' => 'Baktat']);
		array_push($marques_list, ['nom' => 'Balade']);
		array_push($marques_list, ['nom' => 'Balconi']);
		array_push($marques_list, ['nom' => 'Balisto']);
		array_push($marques_list, ['nom' => 'Balthor']);
		array_push($marques_list, ['nom' => 'Baltic']);
		array_push($marques_list, ['nom' => 'Banania']);
		array_push($marques_list, ['nom' => 'Banette']);
		array_push($marques_list, ['nom' => 'Banga']);
		array_push($marques_list, ['nom' => 'Baresa']);
		array_push($marques_list, ['nom' => 'Barilla']);
		array_push($marques_list, ['nom' => 'Baskalia']);
		array_push($marques_list, ['nom' => 'Bavaria']);
		array_push($marques_list, ['nom' => 'BDG']);
		array_push($marques_list, ['nom' => 'Becel']);
		array_push($marques_list, ['nom' => 'Bedros et Savino']);
		array_push($marques_list, ['nom' => 'Beendhi']);
		array_push($marques_list, ['nom' => 'BEghin Say']);
		array_push($marques_list, ['nom' => 'Bel']);
		array_push($marques_list, ['nom' => 'Belin']);
		array_push($marques_list, ['nom' => 'Bell']);
		array_push($marques_list, ['nom' => 'Bell Plantation']);
		array_push($marques_list, ['nom' => 'Belle France']);
		array_push($marques_list, ['nom' => 'Belledonne']);
		array_push($marques_list, ['nom' => "Ben & Jerry's"]);
		array_push($marques_list, ['nom' => 'Benco']);
		array_push($marques_list, ['nom' => 'BEnEdicta']);
		array_push($marques_list, ['nom' => 'BEnEnuts']);
		array_push($marques_list, ['nom' => 'Benevita']);
		array_push($marques_list, ['nom' => 'Bernard Gaborit']);
		array_push($marques_list, ['nom' => 'Bernard Jarnoux']);
		array_push($marques_list, ['nom' => "Beurlay"]);
		array_push($marques_list, ['nom' => "Biaform"]);
		array_push($marques_list, ['nom' => "Bien"]);
		array_push($marques_list, ['nom' => "Big Bang CErEales"]);
		array_push($marques_list, ['nom' => "Bigard"]);
		array_push($marques_list, ['nom' => "Bijou"]);
		array_push($marques_list, ['nom' => "Bio la Vie"]);
		array_push($marques_list, ['nom' => "Bio Nat"]);
		array_push($marques_list, ['nom' => "Bio PlanEte"]);
		array_push($marques_list, ['nom' => "Bio Soleil"]);
		array_push($marques_list, ['nom' => "Bio Sonne"]);
		array_push($marques_list, ['nom' => "Bio Verde"]);
		array_push($marques_list, ['nom' => "Bio Wise"]);
		array_push($marques_list, ['nom' => "BioBleud"]);
		array_push($marques_list, ['nom' => "Biochamps"]);
		array_push($marques_list, ['nom' => "Biofournil"]);
		array_push($marques_list, ['nom' => "Bionade"]);
		array_push($marques_list, ['nom' => "BiOrigine"]);
		array_push($marques_list, ['nom' => "Biosagesse"]);
		array_push($marques_list, ['nom' => "Biosystem"]);
		array_push($marques_list, ['nom' => "Biotech USA"]);
		array_push($marques_list, ['nom' => "Biothentic"]);
		array_push($marques_list, ['nom' => "Biotta"]);
		array_push($marques_list, ['nom' => "Biscuiterie d'Agen"]);
		array_push($marques_list, ['nom' => "Biscuiterie de l'Abbaye"]);
		array_push($marques_list, ['nom' => "Biscuiterie de Provence"]);
		array_push($marques_list, ['nom' => "Biscuits Mistral"]);
		array_push($marques_list, ['nom' => "Bisson"]);
		array_push($marques_list, ['nom' => "Bistro Vite"]);
		array_push($marques_list, ['nom' => "Bjorg"]);
		array_push($marques_list, ['nom' => "Black Protein"]);
		array_push($marques_list, ['nom' => "Bladi"]);
		array_push($marques_list, ['nom' => "BlEdina"]);
		array_push($marques_list, ['nom' => "Blini"]);
		array_push($marques_list, ['nom' => "Bloch"]);
		array_push($marques_list, ['nom' => "Blue Dragon"]);
		array_push($marques_list, ['nom' => "BN"]);
		array_push($marques_list, ['nom' => "Bocaron"]);
		array_push($marques_list, ['nom' => "Body&Fit"]);
		array_push($marques_list, ['nom' => "Bodybuilding Nation"]);
		array_push($marques_list, ['nom' => "Bodymass"]);
		array_push($marques_list, ['nom' => "Bodyraise"]);
		array_push($marques_list, ['nom' => "Boehli"]);
		array_push($marques_list, ['nom' => "Bofrost"]);
		array_push($marques_list, ['nom' => "Bolero"]);
		array_push($marques_list, ['nom' => "Bonbel"]);
		array_push($marques_list, ['nom' => "Bonduelle"]);
		array_push($marques_list, ['nom' => "Boni"]);
		array_push($marques_list, ['nom' => "Bonjour Campagne"]);
		array_push($marques_list, ['nom' => "Bonne Maman"]);
		array_push($marques_list, ['nom' => "Bonneterre"]);
		array_push($marques_list, ['nom' => "Bon-Ri"]);
		array_push($marques_list, ['nom' => "Bons Mayennais"]);
		array_push($marques_list, ['nom' => "Bonta Divina"]);
		array_push($marques_list, ['nom' => "Borde"]);
		array_push($marques_list, ['nom' => "Bordeau Chesnel"]);
		array_push($marques_list, ['nom' => "Borsa"]);
		array_push($marques_list, ['nom' => "Bosto"]);
		array_push($marques_list, ['nom' => "Bougon"]);
		array_push($marques_list, ['nom' => "Bounty"]);
		array_push($marques_list, ['nom' => "Bourdon"]);
		array_push($marques_list, ['nom' => "Boursault"]);
		array_push($marques_list, ['nom' => "Boursin"]);
		array_push($marques_list, ['nom' => "Breizh Cola"]);
		array_push($marques_list, ['nom' => "Brennos"]);
		array_push($marques_list, ['nom' => "Bresse Bleu"]);
		array_push($marques_list, ['nom' => "Bret's"]);
		array_push($marques_list, ['nom' => "Briau"]);
		array_push($marques_list, ['nom' => "Bridel"]);
		array_push($marques_list, ['nom' => "BridElice"]);
		array_push($marques_list, ['nom' => "Bridelight"]);
		array_push($marques_list, ['nom' => "Brioche Pasquier"]);
		array_push($marques_list, ['nom' => "BrocEliande"]);
		array_push($marques_list, ['nom' => "Brossard"]);
		array_push($marques_list, ['nom' => "Brousse Vergez"]);
		array_push($marques_list, ['nom' => "Brun"]);
		array_push($marques_list, ['nom' => "Brut de Coques"]);
		array_push($marques_list, ['nom' => "BSN"]);
		array_push($marques_list, ['nom' => "Buckler"]);
		array_push($marques_list, ['nom' => "Buddy Fruits"]);
		array_push($marques_list, ['nom' => "Buitoni"]);
		array_push($marques_list, ['nom' => "Bulk Powders"]);
		array_push($marques_list, ['nom' => "Burn"]);
		array_push($marques_list, ['nom' => "Cacolac"]);
		array_push($marques_list, ['nom' => "Cadbury"]);
		array_push($marques_list, ['nom' => "CafE Royal"]);
		array_push($marques_list, ['nom' => "Cailler"]);
		array_push($marques_list, ['nom' => "Câlin"]);
		array_push($marques_list, ['nom' => "CalvE"]);
		array_push($marques_list, ['nom' => "Camarillo"]);
		array_push($marques_list, ['nom' => "Camille Bloch"]);
		array_push($marques_list, ['nom' => "Campagne de France"]);
		array_push($marques_list, ['nom' => "Campaillette"]);
		array_push($marques_list, ['nom' => "CampaniEre"]);
		array_push($marques_list, ['nom' => "Campbell's"]);
		array_push($marques_list, ['nom' => "Campina"]);
		array_push($marques_list, ['nom' => "Campo Largo"]);
		array_push($marques_list, ['nom' => "Canada Dry"]);
		array_push($marques_list, ['nom' => "Canadou"]);
		array_push($marques_list, ['nom' => "Canderel"]);
		array_push($marques_list, ['nom' => "Candia"]);
		array_push($marques_list, ['nom' => "Caotina"]);
		array_push($marques_list, ['nom' => "Cap OcEan"]);
		array_push($marques_list, ['nom' => "Capitaine Cook"]);
		array_push($marques_list, ['nom' => "Capitoul"]);
		array_push($marques_list, ['nom' => "Caprice des Dieux"]);
		array_push($marques_list, ['nom' => "Capri-Sun"]);
		array_push($marques_list, ['nom' => "Carambar"]);
		array_push($marques_list, ['nom' => "Carapelli"]);
		array_push($marques_list, ['nom' => "Caresse Antillaise"]);
		array_push($marques_list, ['nom' => "Carte D'Or"]);
		array_push($marques_list, ['nom' => "Carte Nature"]);
		array_push($marques_list, ['nom' => "Carte Noire"]);
		array_push($marques_list, ['nom' => "Casa Azzurra"]);
		array_push($marques_list, ['nom' => "Casa Morando"]);
		array_push($marques_list, ['nom' => "Cassegrain"]);
		array_push($marques_list, ['nom' => "Castagno"]);
		array_push($marques_list, ['nom' => "Castelli"]);
		array_push($marques_list, ['nom' => "Catago"]);
		array_push($marques_list, ['nom' => "Cauvin"]);
		array_push($marques_list, ['nom' => "CEcEmel"]);
		array_push($marques_list, ['nom' => "CElEbrations"]);
		array_push($marques_list, ['nom' => "CelliFlore"]);
		array_push($marques_list, ['nom' => "Celnat"]);
		array_push($marques_list, ['nom' => "CEmoi"]);
		array_push($marques_list, ['nom' => "Cenovis"]);
		array_push($marques_list, ['nom' => "CErEal Bio"]);
		array_push($marques_list, ['nom' => "CErEalpes"]);
		array_push($marques_list, ['nom' => "Ceres"]);
		array_push($marques_list, ['nom' => "CEsar Moroni"]);
		array_push($marques_list, ['nom' => "Chamois d'Or"]);
		array_push($marques_list, ['nom' => "Champomy"]);
		array_push($marques_list, ['nom' => "Chante Saire"]);
		array_push($marques_list, ['nom' => "Chao'an"]);
		array_push($marques_list, ['nom' => "Charal"]);
		array_push($marques_list, ['nom' => "Charles & Alice"]);
		array_push($marques_list, ['nom' => "Charles Antona"]);
		array_push($marques_list, ['nom' => "Charles Faraud"]);
		array_push($marques_list, ['nom' => "Charles Vignon"]);
		array_push($marques_list, ['nom' => "ChaussEe aux Moines"]);
		array_push($marques_list, ['nom' => "Chavroux"]);
		array_push($marques_list, ['nom' => "Cheetos"]);
		array_push($marques_list, ['nom' => "Chez Raynal"]);
		array_push($marques_list, ['nom' => "Chimay"]);
		array_push($marques_list, ['nom' => "Chupa Chups"]);
		array_push($marques_list, ['nom' => "Ciao Carb"]);
		array_push($marques_list, ['nom' => "Cidou"]);
		array_push($marques_list, ['nom' => "Cirio"]);
		array_push($marques_list, ['nom' => "CitE Marine"]);
		array_push($marques_list, ['nom' => "Citterio"]);
		array_push($marques_list, ['nom' => "Clarou"]);
		array_push($marques_list, ['nom' => "ClEment Faugier"]);
		array_push($marques_list, ['nom' => "Clif Bar"]);
		array_push($marques_list, ['nom' => "Coca-Cola"]);
		array_push($marques_list, ['nom' => "Cochonou"]);
		array_push($marques_list, ['nom' => "Cock Brand"]);
		array_push($marques_list, ['nom' => "Coeur de Lion"]);
		array_push($marques_list, ['nom' => "Colona"]);
		array_push($marques_list, ['nom' => "Compagnie du Saumon"]);
		array_push($marques_list, ['nom' => "Compal"]);
		array_push($marques_list, ['nom' => "Comptoir Sushi"]);
		array_push($marques_list, ['nom' => "Comptoirs & Compagnies"]);
		array_push($marques_list, ['nom' => "Comtesse du Barry"]);
		array_push($marques_list, ['nom' => "Conad"]);
		array_push($marques_list, ['nom' => "Confirella"]);
		array_push($marques_list, ['nom' => "Confiturelle"]);
		array_push($marques_list, ['nom' => "ConnEtable"]);
		array_push($marques_list, ['nom' => "Contrex"]);
		array_push($marques_list, ['nom' => "Coquelicot Provence"]);
		array_push($marques_list, ['nom' => "Coraya"]);
		array_push($marques_list, ['nom' => "Corgenic"]);
		array_push($marques_list, ['nom' => "Cornetto"]);
		array_push($marques_list, ['nom' => "Corny"]);
		array_push($marques_list, ['nom' => "Corona"]);
		array_push($marques_list, ['nom' => "Corsica"]);
		array_push($marques_list, ['nom' => "Cortas"]);
		array_push($marques_list, ['nom' => "Costa"]);
		array_push($marques_list, ['nom' => "Costes"]);
		array_push($marques_list, ['nom' => "Côte d'Or"]);
		array_push($marques_list, ['nom' => "CôtE Table"]);
		array_push($marques_list, ['nom' => "Côteaux Nantais"]);
		array_push($marques_list, ['nom' => "CoudEne"]);
		array_push($marques_list, ['nom' => "Courmayeur"]);
		array_push($marques_list, ['nom' => "Cousteron"]);
		array_push($marques_list, ['nom' => "Cracotte"]);
		array_push($marques_list, ['nom' => "CrEaline"]);
		array_push($marques_list, ['nom' => "Creazioni d'Italia"]);
		array_push($marques_list, ['nom' => "CrEolailles"]);
		array_push($marques_list, ['nom' => "Creolay"]);
		array_push($marques_list, ['nom' => "Crespo"]);
		array_push($marques_list, ['nom' => "Cristaline"]);
		array_push($marques_list, ['nom' => "Croc'frais"]);
		array_push($marques_list, ['nom' => "Crop's"]);
		array_push($marques_list, ['nom' => "Croquez Bio"]);
		array_push($marques_list, ['nom' => "Crousti Vol"]);
		array_push($marques_list, ['nom' => "Croustia"]);
		array_push($marques_list, ['nom' => "Croustipate"]);
		array_push($marques_list, ['nom' => "Croustisud"]);
		array_push($marques_list, ['nom' => "Crubio"]);
		array_push($marques_list, ['nom' => "Cruscana"]);
		array_push($marques_list, ['nom' => "Cyril Pinabel"]);
		array_push($marques_list, ['nom' => "Cytosport"]);
		array_push($marques_list, ['nom' => "Czon"]);
		array_push($marques_list, ['nom' => "D&L"]);
		array_push($marques_list, ['nom' => "Daco Bello"]);
		array_push($marques_list, ['nom' => "Daddy"]);
		array_push($marques_list, ['nom' => "Daim"]);
		array_push($marques_list, ['nom' => "Dakatine"]);
		array_push($marques_list, ['nom' => "Dambert"]);
		array_push($marques_list, ['nom' => "Damiano"]);
		array_push($marques_list, ['nom' => "Danao"]);
		array_push($marques_list, ['nom' => "Daniel Dessaint Traiteur"]);
		array_push($marques_list, ['nom' => "Danival"]);
		array_push($marques_list, ['nom' => "Danone"]);
		array_push($marques_list, ['nom' => "Danette"]);
		array_push($marques_list, ['nom' => "Dar Vida"]);
		array_push($marques_list, ['nom' => "Dardenne"]);
		array_push($marques_list, ['nom' => "Dari"]);
		array_push($marques_list, ['nom' => "d'Aucy"]);
		array_push($marques_list, ['nom' => "Daufruit"]);
		array_push($marques_list, ['nom' => "Daunat"]);
		array_push($marques_list, ['nom' => "Daunature"]);
		array_push($marques_list, ['nom' => "De Cecco"]);
		array_push($marques_list, ['nom' => "DEcathlon"]);
		array_push($marques_list, ['nom' => "Defroidmont"]);
		array_push($marques_list, ['nom' => "Del Monte"]);
		array_push($marques_list, ['nom' => "Delacre"]);
		array_push($marques_list, ['nom' => "Delical"]);
		array_push($marques_list, ['nom' => "Delicemer"]);
		array_push($marques_list, ['nom' => "DElifin"]);
		array_push($marques_list, ['nom' => "Delpeyrat"]);
		array_push($marques_list, ['nom' => "Delpierre"]);
		array_push($marques_list, ['nom' => "Demeter"]);
		array_push($marques_list, ['nom' => "Denner"]);
		array_push($marques_list, ['nom' => "Desperados"]);
		array_push($marques_list, ['nom' => "Destination Premium"]);
		array_push($marques_list, ['nom' => "Destrier"]);
		array_push($marques_list, ['nom' => "Diego"]);
		array_push($marques_list, ['nom' => "Diet Avantage"]);
		array_push($marques_list, ['nom' => "Diet Avenue"]);
		array_push($marques_list, ['nom' => "Diet SKN"]);
		array_push($marques_list, ['nom' => "Dietline"]);
		array_push($marques_list, ['nom' => "Direct Producteurs"]);
		array_push($marques_list, ['nom' => "Disney"]);
		array_push($marques_list, ['nom' => "Dolce Gusto"]);
		array_push($marques_list, ['nom' => "Dole"]);
		array_push($marques_list, ['nom' => "Domino"]);
		array_push($marques_list, ['nom' => "Don Simon"]);
		array_push($marques_list, ['nom' => "Doritos"]);
		array_push($marques_list, ['nom' => "Dorset Cereals"]);
		array_push($marques_list, ['nom' => "Douce France"]);
		array_push($marques_list, ['nom' => "Doucelune"]);
		array_push($marques_list, ['nom' => "Doux"]);
		array_push($marques_list, ['nom' => "Dr Karg's"]);
		array_push($marques_list, ['nom' => "Dr Pepper"]);
		array_push($marques_list, ['nom' => "Dr. Oetker"]);
		array_push($marques_list, ['nom' => "Duc de Coeur"]);
		array_push($marques_list, ['nom' => "Ducros"]);
		array_push($marques_list, ['nom' => "Duerr's"]);
		array_push($marques_list, ['nom' => "Dulcesol"]);
		array_push($marques_list, ['nom' => "Duvel"]);
		array_push($marques_list, ['nom' => "Dymatize"]);
		array_push($marques_list, ['nom' => "E. Graindorge"]);
		array_push($marques_list, ['nom' => "Eafit"]);
		array_push($marques_list, ['nom' => "Easy Body"]);
		array_push($marques_list, ['nom' => "Easy Way"]);
		array_push($marques_list, ['nom' => "EasyDiet"]);
		array_push($marques_list, ['nom' => "Eat Natural"]);
		array_push($marques_list, ['nom' => "Ebly"]);
		array_push($marques_list, ['nom' => "Ecochard"]);
		array_push($marques_list, ['nom' => "EcoMil"]);
		array_push($marques_list, ['nom' => "Ecrin des Champs"]);
		array_push($marques_list, ['nom' => "Edel"]);
		array_push($marques_list, ['nom' => "Eden Origine"]);
		array_push($marques_list, ['nom' => "Ederki"]);
		array_push($marques_list, ['nom' => "Effea"]);
		array_push($marques_list, ['nom' => "Effi"]);
		array_push($marques_list, ['nom' => "Eismann"]);
		array_push($marques_list, ['nom' => "Eiyolab"]);
		array_push($marques_list, ['nom' => "El Almendro"]);
		array_push($marques_list, ['nom' => "El Tequito"]);
		array_push($marques_list, ['nom' => "Elephant"]);
		array_push($marques_list, ['nom' => "Elle & Vire"]);
		array_push($marques_list, ['nom' => "Emco"]);
		array_push($marques_list, ['nom' => "Emile NoEl"]);
		array_push($marques_list, ['nom' => "Emmi"]);
		array_push($marques_list, ['nom' => "Energus 10"]);
		array_push($marques_list, ['nom' => "Energy Diet"]);
		array_push($marques_list, ['nom' => "Ensemble"]);
		array_push($marques_list, ['nom' => "Entr'acte"]);
		array_push($marques_list, ['nom' => "Entremont"]);
		array_push($marques_list, ['nom' => "Envia"]);
		array_push($marques_list, ['nom' => "Epi d'or"]);
		array_push($marques_list, ['nom' => "Equitable"]);
		array_push($marques_list, ['nom' => "Erhard"]);
		array_push($marques_list, ['nom' => "Eric Favre"]);
		array_push($marques_list, ['nom' => "Eridanous"]);
		array_push($marques_list, ['nom' => "Ermitage"]);
		array_push($marques_list, ['nom' => "ErtE"]);
		array_push($marques_list, ['nom' => "Escal"]);
		array_push($marques_list, ['nom' => "Eskiss"]);
		array_push($marques_list, ['nom' => "Espuna"]);
		array_push($marques_list, ['nom' => "Ethiquable"]);
		array_push($marques_list, ['nom' => "Ethnoscience"]);
		array_push($marques_list, ['nom' => "Etoile d'Or"]);
		array_push($marques_list, ['nom' => "Etorki"]);
		array_push($marques_list, ['nom' => "Etre Bio"]);
		array_push($marques_list, ['nom' => "Evernat"]);
		array_push($marques_list, ['nom' => "Evian"]);
		array_push($marques_list, ['nom' => "Exotic Food"]);
		array_push($marques_list, ['nom' => "ExtrEme"]);
		array_push($marques_list, ['nom' => "Fage"]);
		array_push($marques_list, ['nom' => "Fanta"]);
		array_push($marques_list, ['nom' => "Farmer"]);
		array_push($marques_list, ['nom' => "Fauchon"]);
		array_push($marques_list, ['nom' => "Fauquet"]);
		array_push($marques_list, ['nom' => "Favrichon"]);
		array_push($marques_list, ['nom' => "Fenioux"]);
		array_push($marques_list, ['nom' => "Ferme Collet"]);
		array_push($marques_list, ['nom' => "Ferme d'Anchin"]);
		array_push($marques_list, ['nom' => "Ferme des Peupliers"]);
		array_push($marques_list, ['nom' => "Ferrero"]);
		array_push($marques_list, ['nom' => "Fibre One"]);
		array_push($marques_list, ['nom' => "Ficello"]);
		array_push($marques_list, ['nom' => "Filet Bleu"]);
		array_push($marques_list, ['nom' => "Findus"]);
		array_push($marques_list, ['nom' => "Fini"]);
		array_push($marques_list, ['nom' => "Finley"]);
		array_push($marques_list, ['nom' => "Fiorentini"]);
		array_push($marques_list, ['nom' => "Firenze"]);
		array_push($marques_list, ['nom' => "First Iron Systems"]);
		array_push($marques_list, ['nom' => "Fischer"]);
		array_push($marques_list, ['nom' => "Fisherman's Friend"]);
		array_push($marques_list, ['nom' => "Fitness Boutique"]);
		array_push($marques_list, ['nom' => "Fittea"]);
		array_push($marques_list, ['nom' => "Flamant Vert"]);
		array_push($marques_list, ['nom' => "Fleurs Des Champs"]);
		array_push($marques_list, ['nom' => "Fleury Michon"]);
		array_push($marques_list, ['nom' => "Flor de Burgos"]);
		array_push($marques_list, ['nom' => "Floraline"]);
		array_push($marques_list, ['nom' => "Florelli"]);
		array_push($marques_list, ['nom' => "Florentin"]);
		array_push($marques_list, ['nom' => "Floressance"]);
		array_push($marques_list, ['nom' => "Florette"]);
		array_push($marques_list, ['nom' => "Fluff"]);
		array_push($marques_list, ['nom' => "Fol Epi"]);
		array_push($marques_list, ['nom' => "Fontaine SantE"]);
		array_push($marques_list, ['nom' => "Fontaneda"]);
		array_push($marques_list, ['nom' => "Foodspring"]);
		array_push($marques_list, ['nom' => "Force Bio"]);
		array_push($marques_list, ['nom' => "Forestine"]);
		array_push($marques_list, ['nom' => "Forever"]);
		array_push($marques_list, ['nom' => "Fortwenger"]);
		array_push($marques_list, ['nom' => "Fossier"]);
		array_push($marques_list, ['nom' => "FournEe DorEe"]);
		array_push($marques_list, ['nom' => "Française de Gastronomie"]);
		array_push($marques_list, ['nom' => "France Aglut Bio"]);
		array_push($marques_list, ['nom' => "Francine"]);
		array_push($marques_list, ['nom' => "François ThEron"]);
		array_push($marques_list, ['nom' => "Fratelli Laurieri"]);
		array_push($marques_list, ['nom' => "Freedent"]);
		array_push($marques_list, ['nom' => "Fresh Gourmet"]);
		array_push($marques_list, ['nom' => "Fresubin"]);
		array_push($marques_list, ['nom' => "Fritelle"]);
		array_push($marques_list, ['nom' => "Fruit d'Or"]);
		array_push($marques_list, ['nom' => "FruitE"]);
		array_push($marques_list, ['nom' => "Fruittella"]);
		array_push($marques_list, ['nom' => "Gaia"]);
		array_push($marques_list, ['nom' => "Galaxi"]);
		array_push($marques_list, ['nom' => "Galaxy"]);
		array_push($marques_list, ['nom' => "Galbani"]);
		array_push($marques_list, ['nom' => "Galler"]);
		array_push($marques_list, ['nom' => "Garbit"]);
		array_push($marques_list, ['nom' => "Gardeil"]);
		array_push($marques_list, ['nom' => "Garofalo"]);
		array_push($marques_list, ['nom' => "Gaspari Nutrition"]);
		array_push($marques_list, ['nom' => "Gastromer"]);
		array_push($marques_list, ['nom' => "Gatorade"]);
		array_push($marques_list, ['nom' => "Gavottes"]);
		array_push($marques_list, ['nom' => "Gayelord Hauser"]);
		array_push($marques_list, ['nom' => "GEant Vert"]);
		array_push($marques_list, ['nom' => "Genius"]);
		array_push($marques_list, ['nom' => "GerblE"]);
		array_push($marques_list, ['nom' => "GerlinEa"]);
		array_push($marques_list, ['nom' => "Germline"]);
		array_push($marques_list, ['nom' => "Gervais"]);
		array_push($marques_list, ['nom' => "Get 27"]);
		array_push($marques_list, ['nom' => "Gilbert"]);
		array_push($marques_list, ['nom' => "Gimbert OcEan"]);
		array_push($marques_list, ['nom' => "Gini"]);
		array_push($marques_list, ['nom' => "Ginko"]);
		array_push($marques_list, ['nom' => "Giovanni Ferrari"]);
		array_push($marques_list, ['nom' => "Giovanni Rana"]);
		array_push($marques_list, ['nom' => "Giraudet"]);
		array_push($marques_list, ['nom' => "Globus"]);
		array_push($marques_list, ['nom' => "Gloria"]);
		array_push($marques_list, ['nom' => "Go Tan"]);
		array_push($marques_list, ['nom' => "Golden Bridge"]);
		array_push($marques_list, ['nom' => "Golden Fruit"]);
		array_push($marques_list, ['nom' => "Golden Seafood"]);
		array_push($marques_list, ['nom' => "Good Goût"]);
		array_push($marques_list, ['nom' => "Goulibeur"]);
		array_push($marques_list, ['nom' => "Gourmie's"]);
		array_push($marques_list, ['nom' => "Grace"]);
		array_push($marques_list, ['nom' => "Grain de Frais"]);
		array_push($marques_list, ['nom' => "Granarolo"]);
		array_push($marques_list, ['nom' => "Grand Fermage"]);
		array_push($marques_list, ['nom' => "Grand MEre"]);
		array_push($marques_list, ['nom' => "Grandeur Nature"]);
		array_push($marques_list, ['nom' => "Granini"]);
		array_push($marques_list, ['nom' => "Granoro"]);
		array_push($marques_list, ['nom' => "Grany"]);
		array_push($marques_list, ['nom' => "Green Beverages"]);
		array_push($marques_list, ['nom' => "Green Home"]);
		array_push($marques_list, ['nom' => "GreenShoot"]);
		array_push($marques_list, ['nom' => "Grenade"]);
		array_push($marques_list, ['nom' => "Grillon d'Or"]);
		array_push($marques_list, ['nom' => "Grimbergen"]);
		array_push($marques_list, ['nom' => "Gü"]);
		array_push($marques_list, ['nom' => "Guinness"]);
		array_push($marques_list, ['nom' => "Gullón"]);
		array_push($marques_list, ['nom' => "GustadEa"]);
		array_push($marques_list, ['nom' => "Guyader"]);
		array_push($marques_list, ['nom' => "Guylian"]);
		array_push($marques_list, ['nom' => "Gyma"]);
		array_push($marques_list, ['nom' => "Häagen-Dazs"]);
		array_push($marques_list, ['nom' => "Happy Bio"]);
		array_push($marques_list, ['nom' => "Haribo"]);
		array_push($marques_list, ['nom' => "Harrisons"]);
		array_push($marques_list, ['nom' => "Harry's"]);
		array_push($marques_list, ['nom' => "Harvey"]);
		array_push($marques_list, ['nom' => "Heineken"]);
		array_push($marques_list, ['nom' => "Heinz"]);
		array_push($marques_list, ['nom' => "Heirler"]);
		array_push($marques_list, ['nom' => "HEnaff"]);
		array_push($marques_list, ['nom' => "Henri Raffin"]);
		array_push($marques_list, ['nom' => "HEpar"]);
		array_push($marques_list, ['nom' => "Herbalife"]);
		array_push($marques_list, ['nom' => "Hereford"]);
		array_push($marques_list, ['nom' => "Hermesetas"]);
		array_push($marques_list, ['nom' => "Hero"]);
		array_push($marques_list, ['nom' => "Hershey's"]);
		array_push($marques_list, ['nom' => "Herta"]);
		array_push($marques_list, ['nom' => "Heudebert"]);
		array_push($marques_list, ['nom' => "Hikari Miso"]);
		array_push($marques_list, ['nom' => "Hipp"]);
		array_push($marques_list, ['nom' => "Hod Lavan"]);
		array_push($marques_list, ['nom' => "Hoegaarden"]);
		array_push($marques_list, ['nom' => "Holland Master"]);
		array_push($marques_list, ['nom' => "Hollywood"]);
		array_push($marques_list, ['nom' => "Holy Fruits"]);
		array_push($marques_list, ['nom' => "Horeca"]);
		array_push($marques_list, ['nom' => "IdEe VEgEtale"]);
		array_push($marques_list, ['nom' => "Iglo"]);
		array_push($marques_list, ['nom' => "Ikalia"]);
		array_push($marques_list, ['nom' => "Ikea"]);
		array_push($marques_list, ['nom' => "Iller"]);
		array_push($marques_list, ['nom' => "ImmEdia"]);
		array_push($marques_list, ['nom' => "Impact Nutrition"]);
		array_push($marques_list, ['nom' => "ImpErial"]);
		array_push($marques_list, ['nom' => "Inkospor"]);
		array_push($marques_list, ['nom' => "Innocent"]);
		array_push($marques_list, ['nom' => "Instinct Dessaint Fraîcheur"]);
		array_push($marques_list, ['nom' => "Insudiet"]);
		array_push($marques_list, ['nom' => "IronMaxx"]);
		array_push($marques_list, ['nom' => "Isabel"]);
		array_push($marques_list, ['nom' => "Isali"]);
		array_push($marques_list, ['nom' => "Isaura"]);
		array_push($marques_list, ['nom' => "Isigny Sainte MEre"]);
		array_push($marques_list, ['nom' => "Isio 4"]);
		array_push($marques_list, ['nom' => "Isla DElice"]);
		array_push($marques_list, ['nom' => "Isla Mondial"]);
		array_push($marques_list, ['nom' => "Isola Bio"]);
		array_push($marques_list, ['nom' => "Isostar"]);
		array_push($marques_list, ['nom' => "Istara"]);
		array_push($marques_list, ['nom' => "Italiamo"]);
		array_push($marques_list, ['nom' => "J.D. Gross"]);
		array_push($marques_list, ['nom' => "Jack Daniel's"]);
		array_push($marques_list, ['nom' => "Jack Link's"]);
		array_push($marques_list, ['nom' => "Jacob's"]);
		array_push($marques_list, ['nom' => "Jacques"]);
		array_push($marques_list, ['nom' => "Jacquet"]);
		array_push($marques_list, ['nom' => "Jacquot"]);
		array_push($marques_list, ['nom' => "Jafaden"]);
		array_push($marques_list, ['nom' => "Japan Canteen"]);
		array_push($marques_list, ['nom' => "Jardin Bio"]);
		array_push($marques_list, ['nom' => "Jean Caby"]);
		array_push($marques_list, ['nom' => "Jean HervE"]);
		array_push($marques_list, ['nom' => "Jean Martin"]);
		array_push($marques_list, ['nom' => "Jeff de Bruges"]);
		array_push($marques_list, ['nom' => "Jenny Craig"]);
		array_push($marques_list, ['nom' => "Jentschura"]);
		array_push($marques_list, ['nom' => "Jock"]);
		array_push($marques_list, ['nom' => "Jockey"]);
		array_push($marques_list, ['nom' => "Johnsonville"]);
		array_push($marques_list, ['nom' => "Joker"]);
		array_push($marques_list, ['nom' => "Jordans"]);
		array_push($marques_list, ['nom' => "Juice Plus"]);
		array_push($marques_list, ['nom' => "Juicy Water"]);
		array_push($marques_list, ['nom' => "Jules Destrooper"]);
		array_push($marques_list, ['nom' => "Justin Bridou"]);
		array_push($marques_list, ['nom' => "Juvamine"]);
		array_push($marques_list, ['nom' => "Kailo Brand"]);
		array_push($marques_list, ['nom' => "KaliBio"]);
		array_push($marques_list, ['nom' => "Kambly"]);
		array_push($marques_list, ['nom' => "Kaoka"]);
		array_push($marques_list, ['nom' => "Kara"]);
		array_push($marques_list, ['nom' => "KarElEa"]);
		array_push($marques_list, ['nom' => "Karine & Jeff"]);
		array_push($marques_list, ['nom' => "Karma"]);
		array_push($marques_list, ['nom' => "Kauffer's"]);
		array_push($marques_list, ['nom' => "Kellogg's"]);
		array_push($marques_list, ['nom' => "Ker Cadelac"]);
		array_push($marques_list, ['nom' => "Ker Ronan"]);
		array_push($marques_list, ['nom' => "Kergrist"]);
		array_push($marques_list, ['nom' => "Kikkoman"]);
		array_push($marques_list, ['nom' => "Kiluva"]);
		array_push($marques_list, ['nom' => "Kinder"]);
		array_push($marques_list, ['nom' => "Kiri"]);
		array_push($marques_list, ['nom' => "Kiss Cool"]);
		array_push($marques_list, ['nom' => "Kitkat"]);
		array_push($marques_list, ['nom' => "Knorr"]);
		array_push($marques_list, ['nom' => "Kolios"]);
		array_push($marques_list, ['nom' => "KOT"]);
		array_push($marques_list, ['nom' => "Kraft"]);
		array_push($marques_list, ['nom' => "Krema"]);
		array_push($marques_list, ['nom' => "Krisprolls"]);
		array_push($marques_list, ['nom' => "Kriss-Laure"]);
		array_push($marques_list, ['nom' => "Kritsen"]);
		array_push($marques_list, ['nom' => "Kronenbourg"]);
		array_push($marques_list, ['nom' => "Kühne"]);
		array_push($marques_list, ['nom' => "Kusmi Tea"]);
		array_push($marques_list, ['nom' => "Kwatta"]);
		array_push($marques_list, ['nom' => "La Belle Chaurienne"]);
		array_push($marques_list, ['nom' => "La Belle Etoile"]);
		array_push($marques_list, ['nom' => "La Belle Iloise"]);
		array_push($marques_list, ['nom' => "La Bergerie"]);
		array_push($marques_list, ['nom' => "La Bio Idea"]);
		array_push($marques_list, ['nom' => "La BoulangEre"]);
		array_push($marques_list, ['nom' => "La Bressane"]);
		array_push($marques_list, ['nom' => "La Caldera"]);
		array_push($marques_list, ['nom' => "La Compagnie Artique"]);
		array_push($marques_list, ['nom' => "La CrEpe de BrocEliande"]);
		array_push($marques_list, ['nom' => "La Cuisine d'OcEane"]);
		array_push($marques_list, ['nom' => "La DournEe DorEe"]);
		array_push($marques_list, ['nom' => "La Dunkerquoise"]);
		array_push($marques_list, ['nom' => "La Ferme Biologique"]);
		array_push($marques_list, ['nom' => "La Ferme du ManEge"]);
		array_push($marques_list, ['nom' => "La FermiEre"]);
		array_push($marques_list, ['nom' => "La Finestra Sul Cielo"]);
		array_push($marques_list, ['nom' => "La Gastronomia di Angelo"]);
		array_push($marques_list, ['nom' => "La LaitiEre"]);
		array_push($marques_list, ['nom' => "La Maison du Coco"]);
		array_push($marques_list, ['nom' => "la Maison Guiot"]);
		array_push($marques_list, ['nom' => "La Mandorle"]);
		array_push($marques_list, ['nom' => "La MEre Poulard"]);
		array_push($marques_list, ['nom' => "La Pasta di Angelo"]);
		array_push($marques_list, ['nom' => "La Pie qui Chante"]);
		array_push($marques_list, ['nom' => "La PotagEre"]);
		array_push($marques_list, ['nom' => "La Royale"]);
		array_push($marques_list, ['nom' => "La Salvetat"]);
		array_push($marques_list, ['nom' => "La TisaniEre"]);
		array_push($marques_list, ['nom' => "La Tourangelle"]);
		array_push($marques_list, ['nom' => "La Trinitaine"]);
		array_push($marques_list, ['nom' => "La Vache qui rit"]);
		array_push($marques_list, ['nom' => "La Vosgienne"]);
		array_push($marques_list, ['nom' => "La William"]);
		array_push($marques_list, ['nom' => "Label Rouge"]);
		array_push($marques_list, ['nom' => "Labeyrie"]);
		array_push($marques_list, ['nom' => "Lactel"]);
		array_push($marques_list, ['nom' => "L'Amandaie"]);
		array_push($marques_list, ['nom' => "Landfein"]);
		array_push($marques_list, ['nom' => "Landvika"]);
		array_push($marques_list, ['nom' => "L'Angelus"]);
		array_push($marques_list, ['nom' => "L'AngElys"]);
		array_push($marques_list, ['nom' => "Lanquetot"]);
		array_push($marques_list, ['nom' => "Lanvin"]);
		array_push($marques_list, ['nom' => "Larnaudie"]);
		array_push($marques_list, ['nom' => "L'Assiette Bleue"]);
		array_push($marques_list, ['nom' => "L'atelier Blini"]);
		array_push($marques_list, ['nom' => "Lausse"]);
		array_push($marques_list, ['nom' => "Layenberger"]);
		array_push($marques_list, ['nom' => "Lay's"]);
		array_push($marques_list, ['nom' => "Lazzaretti"]);
		array_push($marques_list, ['nom' => "Le Bercail"]);
		array_push($marques_list, ['nom' => "Le Bon Semeur"]);
		array_push($marques_list, ['nom' => "Le Bonheur est dans le Pot"]);
		array_push($marques_list, ['nom' => "Le Brebiou"]);
		array_push($marques_list, ['nom' => "Le Cabanon"]);
		array_push($marques_list, ['nom' => "Le Cantonnais"]);
		array_push($marques_list, ['nom' => "Le Cavalier"]);
		array_push($marques_list, ['nom' => "Le CEsarin"]);
		array_push($marques_list, ['nom' => "Le Fleurier"]);
		array_push($marques_list, ['nom' => "Le Flutiau"]);
		array_push($marques_list, ['nom' => "Le Forban"]);
		array_push($marques_list, ['nom' => "Le Francomtois"]);
		array_push($marques_list, ['nom' => "Le Gall"]);
		array_push($marques_list, ['nom' => "Le Gaulois"]);
		array_push($marques_list, ['nom' => "Le Guillou"]);
		array_push($marques_list, ['nom' => "Le Jardin de Corentin"]);
		array_push($marques_list, ['nom' => "Le Jardin d'Orante"]);
		array_push($marques_list, ['nom' => "Le Kiosque à Sandwiches"]);
		array_push($marques_list, ['nom' => "Le Moulin du Pivert"]);
		array_push($marques_list, ['nom' => "Le Pain des Fleurs"]);
		array_push($marques_list, ['nom' => "Le Paturon"]);
		array_push($marques_list, ['nom' => "Le Petit Basque"]);
		array_push($marques_list, ['nom' => "Le Phare du Cap Bon"]);
		array_push($marques_list, ['nom' => "Le Picoreur"]);
		array_push($marques_list, ['nom' => "Le Porc Français"]);
		array_push($marques_list, ['nom' => "Le Rustique"]);
		array_push($marques_list, ['nom' => "Le Ster"]);
		array_push($marques_list, ['nom' => "Le Temps des Saisons"]);
		array_push($marques_list, ['nom' => "Le TrEsor des Dieux"]);
		array_push($marques_list, ['nom' => "Le Vieux PanE"]);
		array_push($marques_list, ['nom' => "LEa Nature"]);
		array_push($marques_list, ['nom' => "Lee Kum Kee"]);
		array_push($marques_list, ['nom' => "Leerdammer"]);
		array_push($marques_list, ['nom' => "Leffe"]);
		array_push($marques_list, ['nom' => "Lehmann"]);
		array_push($marques_list, ['nom' => "l'Emile Saveurs"]);
		array_push($marques_list, ['nom' => "Lenny & Larry's"]);
		array_push($marques_list, ['nom' => "Leonidas"]);
		array_push($marques_list, ['nom' => "Lepetit"]);
		array_push($marques_list, ['nom' => "L'Epi"]);
		array_push($marques_list, ['nom' => "Leroux"]);
		array_push($marques_list, ['nom' => "Les 2 Vaches"]);
		array_push($marques_list, ['nom' => "Les 4 Pis"]);
		array_push($marques_list, ['nom' => "Les Artisans du Bio"]);
		array_push($marques_list, ['nom' => "Les BrasErades"]);
		array_push($marques_list, ['nom' => "Les Comtes de Provence"]);
		array_push($marques_list, ['nom' => "Les Crudettes"]);
		array_push($marques_list, ['nom' => "Les DElices de Julienne"]);
		array_push($marques_list, ['nom' => "Les Dieux"]);
		array_push($marques_list, ['nom' => "Les Doris"]);
		array_push($marques_list, ['nom' => "Les Ensoleillades"]);
		array_push($marques_list, ['nom' => "Les Extra Fines du Forez"]);
		array_push($marques_list, ['nom' => "Les Mouettes d'Arvor"]);
		array_push($marques_list, ['nom' => "Les P'tits Chefs du Bio"]);
		array_push($marques_list, ['nom' => "Les Recettes de CEliane"]);
		array_push($marques_list, ['nom' => "Les Renardises"]);
		array_push($marques_list, ['nom' => "Lescure"]);
		array_push($marques_list, ['nom' => "Lesieur"]);
		array_push($marques_list, ['nom' => "L'Etal du Boucher"]);
		array_push($marques_list, ['nom' => "L'Etal du Volailler"]);
		array_push($marques_list, ['nom' => "Liebig"]);
		array_push($marques_list, ['nom' => "Lifefood"]);
		array_push($marques_list, ['nom' => "Lightbody"]);
		array_push($marques_list, ['nom' => "Lima"]);
		array_push($marques_list, ['nom' => "Lincet"]);
		array_push($marques_list, ['nom' => "Lindt"]);
		array_push($marques_list, ['nom' => "Linwoods"]);
		array_push($marques_list, ['nom' => "Lipton"]);
		array_push($marques_list, ['nom' => "L'Italie des Pâtes "]);
		array_push($marques_list, ['nom' => "Lizi's"]);
		array_push($marques_list, ['nom' => "Loacker"]);
		array_push($marques_list, ['nom' => "Lobodis"]);
		array_push($marques_list, ['nom' => "Loïc Raison"]);
		array_push($marques_list, ['nom' => "Longley Farm"]);
		array_push($marques_list, ['nom' => "Look O Look"]);
		array_push($marques_list, ['nom' => "Looza"]);
		array_push($marques_list, ['nom' => "Loprofin"]);
		array_push($marques_list, ['nom' => "Lord Nelson"]);
		array_push($marques_list, ['nom' => "Lorenz"]);
		array_push($marques_list, ['nom' => "Lorina"]);
		array_push($marques_list, ['nom' => "Lotus"]);
		array_push($marques_list, ['nom' => "Lou PErac"]);
		array_push($marques_list, ['nom' => "Louis Martin"]);
		array_push($marques_list, ['nom' => "Lovechock"]);
		array_push($marques_list, ['nom' => "Lovilio"]);
		array_push($marques_list, ['nom' => "LR"]);
		array_push($marques_list, ['nom' => "LU"]);
		array_push($marques_list, ['nom' => "Lucien Georgelin"]);
		array_push($marques_list, ['nom' => "Lune de Miel"]);
		array_push($marques_list, ['nom' => "Lunor"]);
		array_push($marques_list, ['nom' => "Lusitana"]);
		array_push($marques_list, ['nom' => "Lustucru"]);
		array_push($marques_list, ['nom' => "Lutosa"]);
		array_push($marques_list, ['nom' => "Lutti"]);
		array_push($marques_list, ['nom' => "Lynos"]);
		array_push($marques_list, ['nom' => "M&M's"]);
		array_push($marques_list, ['nom' => "Ma Vie sans Gluten"]);
		array_push($marques_list, ['nom' => "Maayane"]);
		array_push($marques_list, ['nom' => "Mc Cain"]);
		array_push($marques_list, ['nom' => "Made Good"]);
		array_push($marques_list, ['nom' => "Madern"]);
		array_push($marques_list, ['nom' => "Madrange"]);
		array_push($marques_list, ['nom' => "Magda"]);
		array_push($marques_list, ['nom' => "Maggi"]);
		array_push($marques_list, ['nom' => "Magnum"]);
		array_push($marques_list, ['nom' => "Maille"]);
		array_push($marques_list, ['nom' => "Maison du CafE"]);
		array_push($marques_list, ['nom' => "Maison Larzul"]);
		array_push($marques_list, ['nom' => "Maison Prunier"]);
		array_push($marques_list, ['nom' => "Maison Tino"]);
		array_push($marques_list, ['nom' => "Maître Coq"]);
		array_push($marques_list, ['nom' => "Maître Jean Pierre"]);
		array_push($marques_list, ['nom' => "Maître Olivier"]);
		array_push($marques_list, ['nom' => "Maître Pierre"]);
		array_push($marques_list, ['nom' => "Maître Prunille"]);
		array_push($marques_list, ['nom' => "Maîtres Laitiers"]);
		array_push($marques_list, ['nom' => "Maizena"]);
		array_push($marques_list, ['nom' => "Makabi"]);
		array_push($marques_list, ['nom' => "Malabar"]);
		array_push($marques_list, ['nom' => "Malibu"]);
		array_push($marques_list, ['nom' => "Malo"]);
		array_push($marques_list, ['nom' => "Malongo"]);
		array_push($marques_list, ['nom' => "Maltagliati"]);
		array_push($marques_list, ['nom' => "Maltesers"]);
		array_push($marques_list, ['nom' => "Mamee"]);
		array_push($marques_list, ['nom' => "Mamie Bio"]);
		array_push($marques_list, ['nom' => "Mamie Nova"]);
		array_push($marques_list, ['nom' => "Mangajo"]);
		array_push($marques_list, ['nom' => "Maple Joe"]);
		array_push($marques_list, ['nom' => "Maredsous"]);
		array_push($marques_list, ['nom' => "Mareval"]);
		array_push($marques_list, ['nom' => "Marie"]);
		array_push($marques_list, ['nom' => "Marie BlachEre"]);
		array_push($marques_list, ['nom' => "Marie Morin"]);
		array_push($marques_list, ['nom' => "MarinoE"]);
		array_push($marques_list, ['nom' => "Markal"]);
		array_push($marques_list, ['nom' => "Mars"]);
		array_push($marques_list, ['nom' => "Martini"]);
		array_push($marques_list, ['nom' => "Mascarin"]);
		array_push($marques_list, ['nom' => "Materne"]);
		array_push($marques_list, ['nom' => "Matines"]);
		array_push($marques_list, ['nom' => "Mavrommatis"]);
		array_push($marques_list, ['nom' => "Max Protein"]);
		array_push($marques_list, ['nom' => "Maximo"]);
		array_push($marques_list, ['nom' => "Maxim's"]);
		array_push($marques_list, ['nom' => "Maxwell House"]);
		array_push($marques_list, ['nom' => "May Tea"]);
		array_push($marques_list, ['nom' => "McCain"]);
		array_push($marques_list, ['nom' => "McEnnedy"]);
		array_push($marques_list, ['nom' => "McVitie's"]);
		array_push($marques_list, ['nom' => "Melfor"]);
		array_push($marques_list, ['nom' => "Menguy's"]);
		array_push($marques_list, ['nom' => "Menier"]);
		array_push($marques_list, ['nom' => "Mentos"]);
		array_push($marques_list, ['nom' => "Merzer"]);
		array_push($marques_list, ['nom' => "Mestemacher"]);
		array_push($marques_list, ['nom' => "Michel Cluizel"]);
		array_push($marques_list, ['nom' => "Michel et Augustin"]);
		array_push($marques_list, ['nom' => "Miko"]);
		array_push($marques_list, ['nom' => "Milaneza"]);
		array_push($marques_list, ['nom' => "Milical"]);
		array_push($marques_list, ['nom' => "Milka"]);
		array_push($marques_list, ['nom' => "Milkyway"]);
		array_push($marques_list, ['nom' => "Milsa"]);
		array_push($marques_list, ['nom' => "Minceur Discount"]);
		array_push($marques_list, ['nom' => "Ming"]);
		array_push($marques_list, ['nom' => "Minute Maid"]);
		array_push($marques_list, ['nom' => "Mission"]);
		array_push($marques_list, ['nom' => "Mississippi Belle"]);
		array_push($marques_list, ['nom' => "Mister Cocktail"]);
		array_push($marques_list, ['nom' => "Miti"]);
		array_push($marques_list, ['nom' => "Mitsuba"]);
		array_push($marques_list, ['nom' => "Mix Buffet"]);
		array_push($marques_list, ['nom' => "Modifast"]);
		array_push($marques_list, ['nom' => "Moisan"]);
		array_push($marques_list, ['nom' => "Molenland"]);
		array_push($marques_list, ['nom' => "Mon Fournil"]);
		array_push($marques_list, ['nom' => "Monarc"]);
		array_push($marques_list, ['nom' => "Monbana"]);
		array_push($marques_list, ['nom' => "Monin"]);
		array_push($marques_list, ['nom' => "Monique Ranou"]);
		array_push($marques_list, ['nom' => "Monster Energy"]);
		array_push($marques_list, ['nom' => "Mont Asie"]);
		array_push($marques_list, ['nom' => "Mont Blanc"]);
		array_push($marques_list, ['nom' => "Mont d'Or"]);
		array_push($marques_list, ['nom' => "Mont PelE"]);
		array_push($marques_list, ['nom' => "Mont Roucous"]);
		array_push($marques_list, ['nom' => "Montagne Noire"]);
		array_push($marques_list, ['nom' => "Montebello"]);
		array_push($marques_list, ['nom' => "Montfleuri"]);
		array_push($marques_list, ['nom' => "Montfort"]);
		array_push($marques_list, ['nom' => "Montignac"]);
		array_push($marques_list, ['nom' => "Montorsi"]);
		array_push($marques_list, ['nom' => "Mora"]);
		array_push($marques_list, ['nom' => "Morato"]);
		array_push($marques_list, ['nom' => "Moreno"]);
		array_push($marques_list, ['nom' => "Moritz"]);
		array_push($marques_list, ['nom' => "Mornflake"]);
		array_push($marques_list, ['nom' => "Moser Roth"]);
		array_push($marques_list, ['nom' => "Motta"]);
		array_push($marques_list, ['nom' => "Moulin de Valdonne"]);
		array_push($marques_list, ['nom' => "Moulin Des Moines"]);
		array_push($marques_list, ['nom' => "Moulin d'Or"]);
		array_push($marques_list, ['nom' => "Mousline"]);
		array_push($marques_list, ['nom' => "Mr Freeze"]);
		array_push($marques_list, ['nom' => "Mr Min"]);
		array_push($marques_list, ['nom' => "Mucci"]);
		array_push($marques_list, ['nom' => "Mulino Bianco"]);
		array_push($marques_list, ['nom' => "Muller"]);
		array_push($marques_list, ['nom' => "Multipower"]);
		array_push($marques_list, ['nom' => "Musclegenix"]);
		array_push($marques_list, ['nom' => "MuscleMeds"]);
		array_push($marques_list, ['nom' => "MusclePharm"]);
		array_push($marques_list, ['nom' => "MuscleTech"]);
		array_push($marques_list, ['nom' => "Mutant"]);
		array_push($marques_list, ['nom' => "Mutant Mass"]);
		array_push($marques_list, ['nom' => "Mutti"]);
		array_push($marques_list, ['nom' => "My Muscle"]);
		array_push($marques_list, ['nom' => "MyMuscle"]);
		array_push($marques_list, ['nom' => "Myprotein"]);
		array_push($marques_list, ['nom' => "MyVegies"]);
		array_push($marques_list, ['nom' => "N.A! Nature Addicts"]);
		array_push($marques_list, ['nom' => "Nakd"]);
		array_push($marques_list, ['nom' => "Naked"]);
		array_push($marques_list, ['nom' => "Nat & Vie"]);
		array_push($marques_list, ['nom' => "Natali"]);
		array_push($marques_list, ['nom' => "Natine"]);
		array_push($marques_list, ['nom' => "Natur Compagnie"]);
		array_push($marques_list, ['nom' => "Natural Mojo"]);
		array_push($marques_list, ['nom' => "Naturalia"]);
		array_push($marques_list, ['nom' => "Nature & Cie"]);
		array_push($marques_list, ['nom' => "Nature Valley"]);
		array_push($marques_list, ['nom' => "Naturela"]);
		array_push($marques_list, ['nom' => "Naturesystem"]);
		array_push($marques_list, ['nom' => "Naturgie"]);
		array_push($marques_list, ['nom' => "NaturGreen"]);
		array_push($marques_list, ['nom' => "Naturhouse"]);
		array_push($marques_list, ['nom' => "Nautilus"]);
		array_push($marques_list, ['nom' => "Navarre"]);
		array_push($marques_list, ['nom' => "Nectaflor"]);
		array_push($marques_list, ['nom' => "Negroni"]);
		array_push($marques_list, ['nom' => "NescafE"]);
		array_push($marques_list, ['nom' => "Nespresso"]);
		array_push($marques_list, ['nom' => "Nesquik"]);
		array_push($marques_list, ['nom' => "Nestea"]);
		array_push($marques_list, ['nom' => "NestlE"]);
		array_push($marques_list, ['nom' => "Neuhauser"]);
		array_push($marques_list, ['nom' => "New Covent Garden Food Co."]);
		array_push($marques_list, ['nom' => "Newtree"]);
		array_push($marques_list, ['nom' => "Next Fitness"]);
		array_push($marques_list, ['nom' => "NHCO"]);
		array_push($marques_list, ['nom' => "Nissin"]);
		array_push($marques_list, ['nom' => "Nixe"]);
		array_push($marques_list, ['nom' => "Nobles"]);
		array_push($marques_list, ['nom' => "Nongshim"]);
		array_push($marques_list, ['nom' => "Nordland"]);
		array_push($marques_list, ['nom' => "Nu"]);
		array_push($marques_list, ['nom' => "Nu3"]);
		array_push($marques_list, ['nom' => "Nutella"]);
		array_push($marques_list, ['nom' => "Nutergia"]);
		array_push($marques_list, ['nom' => "Nutramino"]);
		array_push($marques_list, ['nom' => "Nutrend"]);
		array_push($marques_list, ['nom' => "Nutricia"]);
		array_push($marques_list, ['nom' => "Nutriform"]);
		array_push($marques_list, ['nom' => "Nutrimuscle"]);
		array_push($marques_list, ['nom' => "Nutrisaveurs"]);
		array_push($marques_list, ['nom' => "Nutrytec"]);
		array_push($marques_list, ['nom' => "Oasis"]);
		array_push($marques_list, ['nom' => "Oatly"]);
		array_push($marques_list, ['nom' => "Ocean Spray"]);
		array_push($marques_list, ['nom' => "Of Tov"]);
		array_push($marques_list, ['nom' => "Ofal Bio"]);
		array_push($marques_list, ['nom' => "Officer"]);
		array_push($marques_list, ['nom' => "Oh Yeah"]);
		array_push($marques_list, ['nom' => "Old El Paso"]);
		array_push($marques_list, ['nom' => "Olimp"]);
		array_push($marques_list, ['nom' => "Optimum Nutrition"]);
		array_push($marques_list, ['nom' => "Orangina"]);
		array_push($marques_list, ['nom' => "Oreo"]);
		array_push($marques_list, ['nom' => "Organi"]);
		array_push($marques_list, ['nom' => "Oriental Viandes"]);
		array_push($marques_list, ['nom' => "Osem"]);
		array_push($marques_list, ['nom' => "Oummi"]);
		array_push($marques_list, ['nom' => "Overstim.S"]);
		array_push($marques_list, ['nom' => "Ovive"]);
		array_push($marques_list, ['nom' => "Ovomaltine"]);
		array_push($marques_list, ['nom' => "Pago"]);
		array_push($marques_list, ['nom' => "Painsol"]);
		array_push($marques_list, ['nom' => "Palermo"]);
		array_push($marques_list, ['nom' => "Pampryl"]);
		array_push($marques_list, ['nom' => "Panach'"]);
		array_push($marques_list, ['nom' => "Panealba"]);
		array_push($marques_list, ['nom' => "Panier de Yoplait"]);
		array_push($marques_list, ['nom' => "Panorient"]);
		array_push($marques_list, ['nom' => "Panzani"]);
		array_push($marques_list, ['nom' => "Paradeigma"]);
		array_push($marques_list, ['nom' => "Parampara"]);
		array_push($marques_list, ['nom' => "Parmentier"]);
		array_push($marques_list, ['nom' => "Paso"]);
		array_push($marques_list, ['nom' => "Passendale"]);
		array_push($marques_list, ['nom' => "Passoa"]);
		array_push($marques_list, ['nom' => "Patak's"]);
		array_push($marques_list, ['nom' => "Patigel"]);
		array_push($marques_list, ['nom' => "Patrimoine Gourmand"]);
		array_push($marques_list, ['nom' => "Paul & Louise"]);
		array_push($marques_list, ['nom' => "Paul Heumann"]);
		array_push($marques_list, ['nom' => "Pause Village"]);
		array_push($marques_list, ['nom' => "PavE d'Affinois"]);
		array_push($marques_list, ['nom' => "Pay Pay"]);
		array_push($marques_list, ['nom' => "Pays Gourmand"]);
		array_push($marques_list, ['nom' => "Paysan Breton"]);
		array_push($marques_list, ['nom' => "Paysange"]);
		array_push($marques_list, ['nom' => "PCD"]);
		array_push($marques_list, ['nom' => "Peak"]);
		array_push($marques_list, ['nom' => "Pechalou"]);
		array_push($marques_list, ['nom' => "PEcheries Basques"]);
		array_push($marques_list, ['nom' => "Pelforth"]);
		array_push($marques_list, ['nom' => "Pelletier"]);
		array_push($marques_list, ['nom' => "Pema"]);
		array_push($marques_list, ['nom' => "PEpito"]);
		array_push($marques_list, ['nom' => "Pepperidge Farm"]);
		array_push($marques_list, ['nom' => "Pepsi"]);
		array_push($marques_list, ['nom' => "PEre Dodu"]);
		array_push($marques_list, ['nom' => "Performance"]);
		array_push($marques_list, ['nom' => "Perl'Amande"]);
		array_push($marques_list, ['nom' => "Pernod"]);
		array_push($marques_list, ['nom' => "Perrier"]);
		array_push($marques_list, ['nom' => "Pescamar"]);
		array_push($marques_list, ['nom' => "Pescanova"]);
		array_push($marques_list, ['nom' => "Petit Billy"]);
		array_push($marques_list, ['nom' => "Petit Navire"]);
		array_push($marques_list, ['nom' => "Petitgas"]);
		array_push($marques_list, ['nom' => "Petitjean"]);
		array_push($marques_list, ['nom' => "Phare d'Eckmühl"]);
		array_push($marques_list, ['nom' => "Phytostatine"]);
		array_push($marques_list, ['nom' => "Picard"]);
		array_push($marques_list, ['nom' => "Picon"]);
		array_push($marques_list, ['nom' => "PiE d'Angloys"]);
		array_push($marques_list, ['nom' => "Pierre Clot"]);
		array_push($marques_list, ['nom' => "Pierre Martinet"]);
		array_push($marques_list, ['nom' => "Pierre Schmidt"]);
		array_push($marques_list, ['nom' => "PiLeJe"]);
		array_push($marques_list, ['nom' => "Pilpa"]);
		array_push($marques_list, ['nom' => "Pitch"]);
		array_push($marques_list, ['nom' => "Piton des Neiges"]);
		array_push($marques_list, ['nom' => "Plaisance Bio"]);
		array_push($marques_list, ['nom' => "Planta Fin"]);
		array_push($marques_list, ['nom' => "Plantation"]);
		array_push($marques_list, ['nom' => "Plaza del Sol"]);
		array_push($marques_list, ['nom' => "Poco Loco"]);
		array_push($marques_list, ['nom' => "Poilâne"]);
		array_push($marques_list, ['nom' => "Pokka"]);
		array_push($marques_list, ['nom' => "Polette"]);
		array_push($marques_list, ['nom' => "Pom Bistro"]);
		array_push($marques_list, ['nom' => "Poppies"]);
		array_push($marques_list, ['nom' => "Port Salut"]);
		array_push($marques_list, ['nom' => "Porto Cruz"]);
		array_push($marques_list, ['nom' => "Poulaillon"]);
		array_push($marques_list, ['nom' => "Poulain"]);
		array_push($marques_list, ['nom' => "Power System"]);
		array_push($marques_list, ['nom' => "Powerade"]);
		array_push($marques_list, ['nom' => "Powerbar"]);
		array_push($marques_list, ['nom' => "PrEsident"]);
		array_push($marques_list, ['nom' => "Pressade"]);
		array_push($marques_list, ['nom' => "PrimEal"]);
		array_push($marques_list, ['nom' => "Primeur"]);
		array_push($marques_list, ['nom' => "PrimevEre"]);
		array_push($marques_list, ['nom' => "Princes"]);
		array_push($marques_list, ['nom' => "Pringles"]);
		array_push($marques_list, ['nom' => "Prosain"]);
		array_push($marques_list, ['nom' => "Protech"]);
		array_push($marques_list, ['nom' => "ProtEifine"]);
		array_push($marques_list, ['nom' => "Protein World"]);
		array_push($marques_list, ['nom' => "Proti Diet"]);
		array_push($marques_list, ['nom' => "Protifast"]);
		array_push($marques_list, ['nom' => "Provamel"]);
		array_push($marques_list, ['nom' => "Prozis"]);
		array_push($marques_list, ['nom' => "Pruni"]);
		array_push($marques_list, ['nom' => "PSP"]);
		array_push($marques_list, ['nom' => "P'tit Louis"]);
		array_push($marques_list, ['nom' => "Puget"]);
		array_push($marques_list, ['nom' => "Pulco"]);
		array_push($marques_list, ['nom' => "Punch Power"]);
		array_push($marques_list, ['nom' => "Pural"]);
		array_push($marques_list, ['nom' => "Purasana"]);
		array_push($marques_list, ['nom' => "Pure Via"]);
		array_push($marques_list, ['nom' => "QNT"]);
		array_push($marques_list, ['nom' => "Quaker"]);
		array_push($marques_list, ['nom' => "Questbar"]);
		array_push($marques_list, ['nom' => "Quezac"]);
		array_push($marques_list, ['nom' => "Quorn"]);
		array_push($marques_list, ['nom' => "Rapunzel"]);
		array_push($marques_list, ['nom' => "Raynal et Roquelaure"]);
		array_push($marques_list, ['nom' => "REa"]);
		array_push($marques_list, ['nom' => "Red Bull"]);
		array_push($marques_list, ['nom' => "Reese's"]);
		array_push($marques_list, ['nom' => "Reflets de France"]);
		array_push($marques_list, ['nom' => "Reflex"]);
		array_push($marques_list, ['nom' => "Regalette"]);
		array_push($marques_list, ['nom' => "Regalo"]);
		array_push($marques_list, ['nom' => "Regent's Park"]);
		array_push($marques_list, ['nom' => "REghalal"]);
		array_push($marques_list, ['nom' => "Regia"]);
		array_push($marques_list, ['nom' => "REgilait"]);
		array_push($marques_list, ['nom' => "REgime Dukan"]);
		array_push($marques_list, ['nom' => "REgime ProtEinE"]);
		array_push($marques_list, ['nom' => "REgis Bahier"]);
		array_push($marques_list, ['nom' => "REvillon"]);
		array_push($marques_list, ['nom' => "RevoGenix"]);
		array_push($marques_list, ['nom' => "Revola"]);
		array_push($marques_list, ['nom' => "Rians"]);
		array_push($marques_list, ['nom' => "Ribeira"]);
		array_push($marques_list, ['nom' => "Ricard"]);
		array_push($marques_list, ['nom' => "RichesMonts"]);
		array_push($marques_list, ['nom' => "Ricola"]);
		array_push($marques_list, ['nom' => "Rigoni di Asiago"]);
		array_push($marques_list, ['nom' => "Rio Mare"]);
		array_push($marques_list, ['nom' => "Riso Gallo"]);
		array_push($marques_list, ['nom' => "Rita"]);
		array_push($marques_list, ['nom' => "Ritter Sport"]);
		array_push($marques_list, ['nom' => "Rivella"]);
		array_push($marques_list, ['nom' => "Rivoire & Carret"]);
		array_push($marques_list, ['nom' => "Riz du Monde"]);
		array_push($marques_list, ['nom' => "Rochambeau"]);
		array_push($marques_list, ['nom' => "Rochefontaine"]);
		array_push($marques_list, ['nom' => "Roger"]);
		array_push($marques_list, ['nom' => "Roland"]);
		array_push($marques_list, ['nom' => "RondelE"]);
		array_push($marques_list, ['nom' => "Roo'bar"]);
		array_push($marques_list, ['nom' => "Rosinski"]);
		array_push($marques_list, ['nom' => "Rotui"]);
		array_push($marques_list, ['nom' => "Roy RenE"]);
		array_push($marques_list, ['nom' => "Royal"]);
		array_push($marques_list, ['nom' => "Royal Champignon"]);
		array_push($marques_list, ['nom' => "Royal Family"]);
		array_push($marques_list, ['nom' => "Royal Greenland"]);
		array_push($marques_list, ['nom' => "Royans"]);
		array_push($marques_list, ['nom' => "Royco"]);
		array_push($marques_list, ['nom' => "Rummo"]);
		array_push($marques_list, ['nom' => "S&B"]);
		array_push($marques_list, ['nom' => "Sabarot"]);
		array_push($marques_list, ['nom' => "Sacla'"]);
		array_push($marques_list, ['nom' => "Saint Agaune"]);
		array_push($marques_list, ['nom' => "Saint Agur"]);
		array_push($marques_list, ['nom' => "Saint Albray"]);
		array_push($marques_list, ['nom' => "Saint Amand"]);
		array_push($marques_list, ['nom' => "Saint Amour"]);
		array_push($marques_list, ['nom' => "Saint Azay"]);
		array_push($marques_list, ['nom' => "Saint Christophe"]);
		array_push($marques_list, ['nom' => "Saint Jean"]);
		array_push($marques_list, ['nom' => "Saint Louis"]);
		array_push($marques_list, ['nom' => "Saint Loup"]);
		array_push($marques_list, ['nom' => "Sainte Lucie"]);
		array_push($marques_list, ['nom' => "Saint-Malo"]);
		array_push($marques_list, ['nom' => "Saitaku"]);
		array_push($marques_list, ['nom' => "Saiwa"]);
		array_push($marques_list, ['nom' => "Salakis"]);
		array_push($marques_list, ['nom' => "Saldac"]);
		array_push($marques_list, ['nom' => "Samia"]);
		array_push($marques_list, ['nom' => "San Pellegrino"]);
		array_push($marques_list, ['nom' => "Santa Fe"]);
		array_push($marques_list, ['nom' => "Santa Lucia"]);
		array_push($marques_list, ['nom' => "Santiveri"]);
		array_push($marques_list, ['nom' => "Sapresti"]);
		array_push($marques_list, ['nom' => "Saupiquet"]);
		array_push($marques_list, ['nom' => "Savannah"]);
		array_push($marques_list, ['nom' => "Saveurs Attitudes"]);
		array_push($marques_list, ['nom' => "Saveurs de Nos REgions"]);
		array_push($marques_list, ['nom' => "Savoie Yaourt"]);
		array_push($marques_list, ['nom' => "Savora"]);
		array_push($marques_list, ['nom' => "Savourel"]);
		array_push($marques_list, ['nom' => "Schär"]);
		array_push($marques_list, ['nom' => "Schneider"]);
		array_push($marques_list, ['nom' => "Schnitzer"]);
		array_push($marques_list, ['nom' => "Scholetta"]);
		array_push($marques_list, ['nom' => "Schwarzwalder"]);
		array_push($marques_list, ['nom' => "Schweppes"]);
		array_push($marques_list, ['nom' => "Scientec Nutrition"]);
		array_push($marques_list, ['nom' => "Scitec Nutrition"]);
		array_push($marques_list, ['nom' => "Secrets d'Energie"]);
		array_push($marques_list, ['nom' => "Seeberger"]);
		array_push($marques_list, ['nom' => "Semenzato"]);
		array_push($marques_list, ['nom' => "Senfas"]);
		array_push($marques_list, ['nom' => "Senoble"]);
		array_push($marques_list, ['nom' => "Sensations d'Asie"]);
		array_push($marques_list, ['nom' => "Senseo"]);
		array_push($marques_list, ['nom' => "Serebis"]);
		array_push($marques_list, ['nom' => "Sevenhills"]);
		array_push($marques_list, ['nom' => "Sibell"]);
		array_push($marques_list, ['nom' => "Sicilia"]);
		array_push($marques_list, ['nom' => "Sigdal"]);
		array_push($marques_list, ['nom' => "Signal"]);
		array_push($marques_list, ['nom' => "Simon"]);
		array_push($marques_list, ['nom' => "Simplement Bon et Bio"]);
		array_push($marques_list, ['nom' => "Sirius"]);
		array_push($marques_list, ['nom' => "Sirop Sport"]);
		array_push($marques_list, ['nom' => "Skippy"]);
		array_push($marques_list, ['nom' => "Skittles"]);
		array_push($marques_list, ['nom' => "Smarties"]);
		array_push($marques_list, ['nom' => "Smirnoff"]);
		array_push($marques_list, ['nom' => "Smiths"]);
		array_push($marques_list, ['nom' => "Snackfit"]);
		array_push($marques_list, ['nom' => "Snickers"]);
		array_push($marques_list, ['nom' => "So Shape"]);
		array_push($marques_list, ['nom' => "So Tasty"]);
		array_push($marques_list, ['nom' => "SociEtE"]);
		array_push($marques_list, ['nom' => "Socopa"]);
		array_push($marques_list, ['nom' => "Sodastream"]);
		array_push($marques_list, ['nom' => "SodebO"]);
		array_push($marques_list, ['nom' => "Sodexo"]);
		array_push($marques_list, ['nom' => "Soezie"]);
		array_push($marques_list, ['nom' => "Soho"]);
		array_push($marques_list, ['nom' => "Soignon"]);
		array_push($marques_list, ['nom' => "Sojabio"]);
		array_push($marques_list, ['nom' => "Sojade"]);
		array_push($marques_list, ['nom' => "Sojami"]);
		array_push($marques_list, ['nom' => "Sojasun"]);
		array_push($marques_list, ['nom' => "Sol & Mar"]);
		array_push($marques_list, ['nom' => "Solens"]);
		array_push($marques_list, ['nom' => "Soto"]);
		array_push($marques_list, ['nom' => "Soubry"]);
		array_push($marques_list, ['nom' => "Soy"]);
		array_push($marques_list, ['nom' => "Soyjoy"]);
		array_push($marques_list, ['nom' => "Spanico"]);
		array_push($marques_list, ['nom' => "SPRING VALLEY"]);
		array_push($marques_list, ['nom' => "Sprite"]);
		array_push($marques_list, ['nom' => "St Hubert"]);
		array_push($marques_list, ['nom' => "St Mamet"]);
		array_push($marques_list, ['nom' => "St Michel"]);
		array_push($marques_list, ['nom' => "St Môret"]);
		array_push($marques_list, ['nom' => "St Rolan"]);
		array_push($marques_list, ['nom' => "STC Nutrition"]);
		array_push($marques_list, ['nom' => "Stoeffler"]);
		array_push($marques_list, ['nom' => "Stoptou"]);
		array_push($marques_list, ['nom' => "Storck"]);
		array_push($marques_list, ['nom' => "Stroppa"]);
		array_push($marques_list, ['nom' => "Suchard"]);
		array_push($marques_list, ['nom' => "Sugarland"]);
		array_push($marques_list, ['nom' => "Sultana"]);
		array_push($marques_list, ['nom' => "Sumol"]);
		array_push($marques_list, ['nom' => "Sun"]);
		array_push($marques_list, ['nom' => "Sunny Delight"]);
		array_push($marques_list, ['nom' => "Sunny Grain"]);
		array_push($marques_list, ['nom' => "Sunny Via"]);
		array_push($marques_list, ['nom' => "Sunwarrior"]);
		array_push($marques_list, ['nom' => "Supplex"]);
		array_push($marques_list, ['nom' => "Suze"]);
		array_push($marques_list, ['nom' => "Suzi Wan"]);
		array_push($marques_list, ['nom' => "Sveltesse"]);
		array_push($marques_list, ['nom' => "Swiss Delice"]);
		array_push($marques_list, ['nom' => "Sylphide"]);
		array_push($marques_list, ['nom' => "Syrtos"]);
		array_push($marques_list, ['nom' => "Taanayel"]);
		array_push($marques_list, ['nom' => "Tabasco"]);
		array_push($marques_list, ['nom' => "Taifun"]);
		array_push($marques_list, ['nom' => "Taillefine"]);
		array_push($marques_list, ['nom' => "Tanoshi"]);
		array_push($marques_list, ['nom' => "Tante HElEne"]);
		array_push($marques_list, ['nom' => "TAO"]);
		array_push($marques_list, ['nom' => "Taranis"]);
		array_push($marques_list, ['nom' => "Tartare Marque"]);
		array_push($marques_list, ['nom' => "Tartex"]);
		array_push($marques_list, ['nom' => "Tassimo"]);
		array_push($marques_list, ['nom' => "Taste of Nature"]);
		array_push($marques_list, ['nom' => "Tastino"]);
		array_push($marques_list, ['nom' => "Taureau AilE"]);
		array_push($marques_list, ['nom' => "Teisseire"]);
		array_push($marques_list, ['nom' => "TempE"]);
		array_push($marques_list, ['nom' => "Tendre et Plus"]);
		array_push($marques_list, ['nom' => "Tendriade"]);
		array_push($marques_list, ['nom' => "Terra Etica"]);
		array_push($marques_list, ['nom' => "Terrasana"]);
		array_push($marques_list, ['nom' => "Terre d'Italia"]);
		array_push($marques_list, ['nom' => "Terres et CErEales Bio"]);
		array_push($marques_list, ['nom' => "TEte Noire"]);
		array_push($marques_list, ['nom' => "Tetley"]);
		array_push($marques_list, ['nom' => "The Berry Company"]);
		array_push($marques_list, ['nom' => "The Bridge"]);
		array_push($marques_list, ['nom' => "The Coconut Collaborative"]);
		array_push($marques_list, ['nom' => "The Protein Works"]);
		array_push($marques_list, ['nom' => "Thiriet"]);
		array_push($marques_list, ['nom' => "Thomy"]);
		array_push($marques_list, ['nom' => "Ti Bio"]);
		array_push($marques_list, ['nom' => "Tic Tac"]);
		array_push($marques_list, ['nom' => "Tien Shan"]);
		array_push($marques_list, ['nom' => "Tipiak"]);
		array_push($marques_list, ['nom' => "Toblerone"]);
		array_push($marques_list, ['nom' => "Too Good"]);
		array_push($marques_list, ['nom' => "Torras"]);
		array_push($marques_list, ['nom' => "Tossolia"]);
		array_push($marques_list, ['nom' => "Toupargel"]);
		array_push($marques_list, ['nom' => "Tourtel"]);
		array_push($marques_list, ['nom' => "TradilEge"]);
		array_push($marques_list, ['nom' => "Traditions d'Asie"]);
		array_push($marques_list, ['nom' => "Tradizioni d'italia"]);
		array_push($marques_list, ['nom' => "Tramier"]);
		array_push($marques_list, ['nom' => "Traou Mad de Pont-Aven"]);
		array_push($marques_list, ['nom' => "Trawlic"]);
		array_push($marques_list, ['nom' => "Treblec"]);
		array_push($marques_list, ['nom' => "TreeTop"]);
		array_push($marques_list, ['nom' => "Trium"]);
		array_push($marques_list, ['nom' => "Tropicana"]);
		array_push($marques_list, ['nom' => "Tropico"]);
		array_push($marques_list, ['nom' => "Tulip"]);
		array_push($marques_list, ['nom' => "Tutti Free"]);
		array_push($marques_list, ['nom' => "Twinings"]);
		array_push($marques_list, ['nom' => "Twix"]);
		array_push($marques_list, ['nom' => "Tyrrells"]);
		array_push($marques_list, ['nom' => "Uberti"]);
		array_push($marques_list, ['nom' => "Ulti"]);
		array_push($marques_list, ['nom' => "Uncle Ben's"]);
		array_push($marques_list, ['nom' => "USN"]);
		array_push($marques_list, ['nom' => "VahinE"]);
		array_push($marques_list, ['nom' => "Vaïvaï"]);
		array_push($marques_list, ['nom' => "Valcrest"]);
		array_push($marques_list, ['nom' => "Valfleuri"]);
		array_push($marques_list, ['nom' => "Valley Foods"]);
		array_push($marques_list, ['nom' => "Valpi Bio"]);
		array_push($marques_list, ['nom' => "Valpiform"]);
		array_push($marques_list, ['nom' => "Valrhona"]);
		array_push($marques_list, ['nom' => "Valtero"]);
		array_push($marques_list, ['nom' => "Van Houten"]);
		array_push($marques_list, ['nom' => "Vandame"]);
		array_push($marques_list, ['nom' => "Vandemoortele"]);
		array_push($marques_list, ['nom' => "Veetee"]);
		array_push($marques_list, ['nom' => "Vegan Deli"]);
		array_push($marques_list, ['nom' => "Verival"]);
		array_push($marques_list, ['nom' => "Verkade"]);
		array_push($marques_list, ['nom' => "Verquin"]);
		array_push($marques_list, ['nom' => "ViadElice"]);
		array_push($marques_list, ['nom' => "Viandox"]);
		array_push($marques_list, ['nom' => "Vichy"]);
		array_push($marques_list, ['nom' => "VICI"]);
		array_push($marques_list, ['nom' => "Vico"]);
		array_push($marques_list, ['nom' => "Vieira"]);
		array_push($marques_list, ['nom' => "Viennetta"]);
		array_push($marques_list, ['nom' => "Vifon"]);
		array_push($marques_list, ['nom' => "Vigean"]);
		array_push($marques_list, ['nom' => "Villa Gusto"]);
		array_push($marques_list, ['nom' => "Villars"]);
		array_push($marques_list, ['nom' => "Vita Coco"]);
		array_push($marques_list, ['nom' => "Vita D'or"]);
		array_push($marques_list, ['nom' => "Vitabio"]);
		array_push($marques_list, ['nom' => "Vitafit"]);
		array_push($marques_list, ['nom' => "Vitamalt"]);
		array_push($marques_list, ['nom' => "Vitaminwater"]);
		array_push($marques_list, ['nom' => "Vitamont"]);
		array_push($marques_list, ['nom' => "Vitaquell"]);
		array_push($marques_list, ['nom' => "Vitarmonyl"]);
		array_push($marques_list, ['nom' => "Vittel"]);
		array_push($marques_list, ['nom' => "Vivani"]);
		array_push($marques_list, ['nom' => "Vivien Paille"]);
		array_push($marques_list, ['nom' => "Vivis"]);
		array_push($marques_list, ['nom' => "Voelkel"]);
		array_push($marques_list, ['nom' => "Volvic"]);
		array_push($marques_list, ['nom' => "Vrai"]);
		array_push($marques_list, ['nom' => "Wai Wai"]);
		array_push($marques_list, ['nom' => "Walden Farms"]);
		array_push($marques_list, ['nom' => "Walkers"]);
		array_push($marques_list, ['nom' => "Wang"]);
		array_push($marques_list, ['nom' => "Wasa"]);
		array_push($marques_list, ['nom' => "Wassila"]);
		array_push($marques_list, ['nom' => "Weetabix"]);
		array_push($marques_list, ['nom' => "Weider"]);
		array_push($marques_list, ['nom' => "Weight Care"]);
		array_push($marques_list, ['nom' => "Weight Watchers"]);
		array_push($marques_list, ['nom' => "Weleda"]);
		array_push($marques_list, ['nom' => "Werther's Original"]);
		array_push($marques_list, ['nom' => "Whaou!"]);
		array_push($marques_list, ['nom' => "Wheaty"]);
		array_push($marques_list, ['nom' => "Wild West"]);
		array_push($marques_list, ['nom' => "Wilkin & Sons"]);
		array_push($marques_list, ['nom' => "William Saurin"]);
		array_push($marques_list, ['nom' => "Wilmersburger"]);
		array_push($marques_list, ['nom' => "Win Nutrition"]);
		array_push($marques_list, ['nom' => "Wofoo"]);
		array_push($marques_list, ['nom' => "Women's Best"]);
		array_push($marques_list, ['nom' => "Wonderful"]);
		array_push($marques_list, ['nom' => "Worldiet"]);
		array_push($marques_list, ['nom' => "Xcore Nutrition "]);
		array_push($marques_list, ['nom' => "Yabon"]);
		array_push($marques_list, ['nom' => "Yakult"]);
		array_push($marques_list, ['nom' => "Yarden"]);
		array_push($marques_list, ['nom' => "Yedo"]);
		array_push($marques_list, ['nom' => "Yeo's"]);
		array_push($marques_list, ['nom' => "Yetigel"]);
		array_push($marques_list, ['nom' => "Yoplait"]);
		array_push($marques_list, ['nom' => "Yumyum"]);
		array_push($marques_list, ['nom' => "Yves Rocher"]);
		array_push($marques_list, ['nom' => "Zakia Halal"]);
		array_push($marques_list, ['nom' => "Zanetti"]);
		array_push($marques_list, ['nom' => "Zapetti"]);
		array_push($marques_list, ['nom' => "Zapi"]);
		array_push($marques_list, ['nom' => "Zespri"]);
		array_push($marques_list, ['nom' => "Zwan"]);
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
	 * Creation des comptes de demos
	 */
	public function loadDemoAccount()
	{
		echo "ENVIRONNEMENT DEMO\n";
		echo "Creation de comptes de demo pour des presentations (Entreprise et admin): ";
		User::firstOrCreate([
			'user_type_id' => '1',
			'email' => 'admin@tasso.com',
			'password' => Hash::make('test'),
			'status' => 'ACTIVE'
		]);
		$this->createTestEntreprises([
			'user_email' => 'contact@tassodelivery.com',
			'user_type_id' => '2',
			'user_status' => 'ACTIVE',
			'entreprise_status' => 'OUVERT',

			'nom_enseigne' => 'Tasso',
			'addresse_contact_entreprise' => '13 avenue de la demo',
			'code_postal_contact_entreprise' => '33000',
			'commune_contact_entreprise' => 'Merignac',

			'email_fact_contact_entreprise' => 'contact@tassodelivery.com',
			'addresse_fact_contact_entreprise' => '14 avenue de la demo',
			'code_postal_fact_entreprise' => '33100',
			'commune_fact_entreprise' => 'Cenon',

			'nom_contact' => 'Rodriguez',
			'prenom_contact' => 'Pierre',
			'telephone_contact' => '0678901025',
			'email_contact' => 'pierre@tasso.com',

			'description' => 'Marketplace locale',
			'siret' => '82748013800011'
		]);
		echo "         OK \n";
	}

	/**
	 * Generation de données afin de pouvoir tester l'application en dur en attendant la
	 * mise en production.
	 */
	public function loadTestEnvironnement()
	{
		echo "ENVIRONNEMENT DE TEST\n";
		echo "Creation de comptes de tests utilisateurs (Entreprise et client)...: ";

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
		 * ENTREPRISE-2, test de status ACTIVE FERME
		 */
		$this->createTestEntreprises([
			'user_email' => 'ent2@dev.com',
			'user_type_id' => '2',
			'user_status' => 'ACTIVE',
			'entreprise_status' => 'FERME',

			'nom_enseigne' => 'Entreprise teste 2',
			'addresse_contact_entreprise' => '13 avenue du teste2',
			'addresse_fact_contact_entreprise' => '14 avenue du teste2',
			'code_postal_contact_entreprise' => '33000',
			'commune_contact_entreprise' => 'Merignac2',
			'email_fact_contact_entreprise' => 'testfact2@mail.com',
			'code_postal_fact_entreprise' => '33200',
			'commune_fact_entreprise' => 'Merignac22',

			'nom_contact' => 'test_nom_contact22',
			'prenom_contact' => 'test_prenom_contact22',
			'telephone_contact' => '0678901025',
			'email_contact' => 'test22@mail.com',

			'description' => 'Description de test2 pour le dev',
			'siret' => '01234567890122'
		]);
		/**
		 * ENTREPRISE-3, test de status ACTIVE ARRET
		 */
		$this->createTestEntreprises([
			'user_email' => 'ent3@dev.com',
			'user_type_id' => '2',
			'user_status' => 'ACTIVE',
			'entreprise_status' => 'ARRET',

			'nom_enseigne' => 'Entreprise teste 3',
			'addresse_contact_entreprise' => '13 avenue du teste3',
			'addresse_fact_contact_entreprise' => '14 avenue du teste3',
			'code_postal_contact_entreprise' => '33000',
			'commune_contact_entreprise' => 'Merignac3',
			'email_fact_contact_entreprise' => 'testfact3@mail.com',
			'code_postal_fact_entreprise' => '33300',
			'commune_fact_entreprise' => 'Merignac33',

			'nom_contact' => 'test_nom_contact33',
			'prenom_contact' => 'test_prenom_contact33',
			'telephone_contact' => '0678901025',
			'email_contact' => 'test33@mail.com',

			'description' => 'Description de test3 pour le dev',
			'siret' => '01234567890123'
		]);
		/**
		 * Entreprise-4, test de status VALIDATION_EN_ATTENTE FERME
		 */
		$this->createTestEntreprises([
			'user_email' => 'ent4@dev.com',
			'user_type_id' => '2',
			'user_status' => 'VALIDATION_EN_ATTENTE',
			'entreprise_status' => 'FERME',

			'nom_enseigne' => 'Entreprise teste 4',
			'addresse_contact_entreprise' => '13 avenue du teste4',
			'addresse_fact_contact_entreprise' => '14 avenue du teste4',
			'code_postal_contact_entreprise' => '33000',
			'commune_contact_entreprise' => 'Merignac4',
			'email_fact_contact_entreprise' => 'testfact4@mail.com',
			'code_postal_fact_entreprise' => '33400',
			'commune_fact_entreprise' => 'Merignac44',

			'nom_contact' => 'test_nom_contact44',
			'prenom_contact' => 'test_prenom_contact44',
			'telephone_contact' => '0678901025',
			'email_contact' => 'test44@mail.com',

			'description' => 'Description de test4 pour le dev',
			'siret' => '01234567890124'
		]);
		/**
		 * Entreprise-5, test de status ACTIVATION_EN_ATTENTE FERME
		 */
		$this->createTestEntreprises([
			'user_email' => 'ent5@dev.com',
			'user_type_id' => '2',
			'user_status' => 'ACTIVATION_EN_ATTENTE',
			'entreprise_status' => 'FERME',

			'nom_enseigne' => 'Entreprise teste 5',
			'addresse_contact_entreprise' => '13 avenue du teste5',
			'addresse_fact_contact_entreprise' => '14 avenue du teste5',
			'code_postal_contact_entreprise' => '33000',
			'commune_contact_entreprise' => 'Merignac5',
			'email_fact_contact_entreprise' => 'testfact5@mail.com',
			'code_postal_fact_entreprise' => '33500',
			'commune_fact_entreprise' => 'Merignac55',

			'nom_contact' => 'test_nom_contact55',
			'prenom_contact' => 'test_prenom_contact55',
			'telephone_contact' => '0678901025',
			'email_contact' => 'test55@mail.com',

			'description' => 'Description de test5 pour le dev',
			'siret' => '01234567890125'
		]);
		/**
		 * Entreprise-6, test de status BAN FERME
		 */
		$this->createTestEntreprises([
			'user_email' => 'ent6@dev.com',
			'user_type_id' => '2',
			'user_status' => 'BAN',
			'entreprise_status' => 'FERME',

			'nom_enseigne' => 'Entreprise teste 6',
			'addresse_contact_entreprise' => '13 avenue du teste6',
			'addresse_fact_contact_entreprise' => '14 avenue du teste6',
			'code_postal_contact_entreprise' => '33000',
			'commune_contact_entreprise' => 'Merignac6',
			'email_fact_contact_entreprise' => 'testfact6@mail.com',
			'code_postal_fact_entreprise' => '33600',
			'commune_fact_entreprise' => 'Merignac66',

			'nom_contact' => 'test_nom_contact66',
			'prenom_contact' => 'test_prenom_contact66',
			'telephone_contact' => '0678901025',
			'email_contact' => 'test66@mail.com',

			'description' => 'Description de test6 pour le dev',
			'siret' => '01234567890126'
		]);

		/**
		 * CLIENT-1, test de status VALIDATION_EN_ATTENTE
		 */
		Client::FirstOrCreate([
			'user_id' => User::firstOrCreate([
				'user_type_id' => '3',
				'email' => 'client1@dev.com',
				'password' => Hash::make('test'),
				'status' => 'VALIDATION_EN_ATTENTE'
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

		/**
		 * CLIENT-2, test de status ACTIVE
		 */
		Client::FirstOrCreate([
			'user_id' => User::firstOrCreate([
				'user_type_id' => '3',
				'email' => 'client2@dev.com',
				'password' => Hash::make('test'),
				'stripe_id' => 'cus_D2y7fl5oL4WX4r',
				'status' => 'ACTIVE'
			])->id,
			'list_groupe_id' =>  [1],
			'nom' => 'nom_dev2',
			'prenom' => 'prenom_dev2',
			'addresse_facturation' => Contact::create(
				[
					'nom' => 'nom_dev2',
					'prenom' => 'prenom_dev2',
					'telephone' => '0666666666',
					'addresse_fact' => '13 avenue du teste2',
					'code_postal_fact' => '33000',
					'ville_fact' => 'Bordeaux'
				])['id'],
			'addresses_livraison' => [Contact::create(
				[
					'nom' => 'nom_dev2',
					'prenom' => 'prenom_dev2',
					'telephone' => '0666666666',
					'addresse' => '10 Rue Bouffard',
					'code_postal' => '33000',
					'ville' => 'Bordeaux'
				])['id']],
			'telephone' => '0666666666'
		]);

		/**
		 * CLIENT-3, test de status BAN
		 */
		Client::FirstOrCreate([
			'user_id' => User::firstOrCreate([
				'user_type_id' => '3',
				'email' => 'client3@dev.com',
				'password' => Hash::make('test'),
				'status' => 'BAN'
			])->id,
			'list_groupe_id' =>  [1],
			'nom' => 'nom_dev3',
			'prenom' => 'prenom_dev3',
			'addresse_facturation' => Contact::create(
				[
					'nom' => 'nom_dev3',
					'prenom' => 'prenom_dev3',
					'telephone' => '0666666666',
					'addresse_fact' => '13 avenue du teste3',
					'code_postal_fact' => '33000',
					'ville_fact' => 'Bordeaux'
				])['id'],
			'addresses_livraison' => [Contact::create(
				[
					'nom' => 'nom_dev3',
					'prenom' => 'prenom_dev3',
					'telephone' => '0666666666',
					'addresse' => '10 Rue Bouffard',
					'code_postal' => '33000',
					'ville' => 'Bordeaux'
				])['id']],
			'telephone' => '0666666666'
		]);

		echo "         OK \n" . "Creation de comptes d'acces ADMIN au site de developpement...: ";

		User::firstOrCreate([
			'user_type_id' => '1',
			'email' => 'admin1',
			'password' => Hash::make('8zU69$!vQ'),
			'password_caisse' => Hash::make(str_random(6)),
			'status' => 'ACTIVE'
		]);

		User::firstOrCreate([
			'user_type_id' => '1',
			'email' => 'admin2',
			'password' => Hash::make('8zU69$!vQ'),
			'password_caisse' => Hash::make(str_random(6)),
			'status' => 'ACTIVE'
		]);

		User::firstOrCreate([
			'user_type_id' => '1',
			'email' => 'admin3',
			'password' => Hash::make('8zU69$!vQ'),
			'password_caisse' => Hash::make(str_random(6)),
			'status' => 'ACTIVE'
		]);

		echo "         OK \n" . "Creation de produits de teste...: (200 produits)... ";

		$unite[0] = "KG";
		$unite[1] = "L";
		$unite[2] = "UNITE";

		for ($i = 0; $i < 200; $i++)
		{
			$status = rand(0,1);

			$random_categorie = CategorieProduit::where(['id' => rand(1,36)])->first();

			$this->createTestProduits([
				'nom' => "Prod $i",
				'description' => str_random(150),
				'famille_id' => $random_categorie->dependances_familles_produits[rand(0,count($random_categorie->dependances_familles_produits)-1)],
				'categorie_id' => $random_categorie->id,
				'type_id' => rand(1,37),
				'marque_id' => rand(1,1400),
				'entreprise_id' => $status == 0 ? rand(1,6) : 0,
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

		echo "         OK \n". "Creation des demandes d'entreprise (test)...: ";

		$types_demande = ['CREATION', 'SUPPRESSION', 'BUG', 'PRODUITS', 'PAIEMENT', 'RDV', 'NON_DEFINI'];

		for ($i = 0; $i < 7; $i++)
		{
			Demande::firstOrCreate([
				'entreprise_id' => '1',
				'ville_id' => '1',
				'type_activite_id' => '12',
				'type' => $types_demande[$i],
				'details' => str_random(60)
			]);
		}

		for ($i = 0; $i < 7; $i++)
		{
			Demande::firstOrCreate([
				'entreprise_id' => '2',
				'ville_id' => '1',
				'type_activite_id' => '27',
				'type' => $types_demande[$i],
				'details' => str_random(60)
			]);
		}

		for ($i = 0; $i < 7; $i++)
		{
			Demande::firstOrCreate([
				'entreprise_id' => '3',
				'ville_id' => '1',
				'type_activite_id' => '7',
				'type' => $types_demande[$i],
				'details' => str_random(60)
			]);
		}

		echo "         OK \n";
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
