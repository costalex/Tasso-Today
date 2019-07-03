<?php

namespace App\Providers;

use App\Abonnement;
use App\CategorieProduit;
use App\Client;
use App\CodePromo;
use App\CodePromoStat;
use App\Contact;
use App\Couleur;
use App\Demande;
use App\Entreprise;
use App\Etagere;
use App\Event;
use App\Facture;
use App\FamilleProduit;
use App\FondEcran;
use App\Groupe;
use App\MarqueProduit;
use App\Paiement;
use App\PanierHistorique;
use App\Policies\UserPolicy;
use App\Produit;
use App\Rayon;
use App\SousRayon;
use App\TypeActivite;
use App\TypeEntreprise;
use App\TypeProduit;
use App\User;
use App\UserType;
use App\Ville;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\AbonnementPolicy;
use App\Policies\CategorieProduitPolicy;
use App\Policies\ClientPolicy;
use App\Policies\CodePromoPolicy;
use App\Policies\CodePromoStatPolicy;
use App\Policies\ContactPolicy;
use App\Policies\CouleurPolicy;
use App\Policies\DemandePolicy;
use App\Policies\EntreprisePolicy;
use App\Policies\EtagerePolicy;
use App\Policies\EventPolicy;
use App\Policies\FacturePolicy;
use App\Policies\FamilleProduitPolicy;
use App\Policies\FondEcranPolicy;
use App\Policies\GroupePolicy;
use App\Policies\MarqueProduitPolicy;
use App\Policies\PaiementPolicy;
use App\Policies\PanierHistoriquePolicy;
use App\Policies\ProduitPolicy;
use App\Policies\RayonPolicy;
use App\Policies\SousRayonPolicy;
use App\Policies\TypeActivitePolicy;
use App\Policies\TypeEntreprisePolicy;
use App\Policies\TypeProduitPolicy;
use App\Policies\UserTypePolicy;
use App\Policies\VillePolicy;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
	    Abonnement::class => AbonnementPolicy::class,
	    CategorieProduit::class => CategorieProduitPolicy::class,
	    Client::class => ClientPolicy::class,
	    CodePromo::class => CodePromoPolicy::class,
	    CodePromoStat::class => CodePromoStatPolicy::class,
	    Contact::class => ContactPolicy::class,
	    Couleur::class => CouleurPolicy::class,
	    Demande::class => DemandePolicy::class,
	    Entreprise::class => EntreprisePolicy::class,
	    Etagere::class => EtagerePolicy::class,
	    Event::class => EventPolicy::class,
	    Facture::class => FacturePolicy::class,
	    FamilleProduit::class => FamilleProduitPolicy::class,
	    FondEcran::class => FondEcranPolicy::class,
	    Groupe::class => GroupePolicy::class,
	    MarqueProduit::class => MarqueProduitPolicy::class,
	    Paiement::class => PaiementPolicy::class,
	    PanierHistorique::class => PanierHistoriquePolicy::class,
	    Produit::class => ProduitPolicy::class,
	    Rayon::class => RayonPolicy::class,
	    SousRayon::class => SousRayonPolicy::class,
	    TypeActivite::class => TypeActivitePolicy::class,
	    TypeEntreprise::class => TypeEntreprisePolicy::class,
	    TypeProduit::class => TypeProduitPolicy::class,
	    User::class => UserPolicy::class,
	    UserType::class => UserTypePolicy::class,
	    Ville::class => VillePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
	    $this->registerPolicies();

	    //Pour le moment la creation de tokens permet de recuperer les datas de connections (stats) des users connectés
//	    Passport::tokensExpireIn(Carbon::now()->addDays(7));
//	    Passport::refreshTokensExpireIn(Carbon::now()->addDays(14));
	    Passport::routes();
//	    Passport::enableImplicitGrant();
    }
}
