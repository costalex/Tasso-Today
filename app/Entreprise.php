<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entreprise extends Model
{
	use SoftDeletes;

	protected $guarded = [
		'id'
	];

    protected $fillable = [
        'user_id', 'abonnement_id', 'type_activite_id',
	    'nom_enseigne', 'type_entreprise_id', 'status',
	    'addresse_entreprise_contact_id', 'contact_entreprise_id', 'description',
	    'siret', 'Coordonnées_GPS', 'ville_id',
	    'horraires_ouverture', 'banniere', 'path_file_logo_entreprise',
	    'liste_produits', 'shop_order', 'facture_commissions',
	    'fonds', 'taille_lots', 'panier_min_general', 'reseaux_sociaux',
	    'date_abonnement'
    ];

    protected $casts = [
        'Coordonnées_GPS' => 'array',
        'horraires_ouverture' => 'array',
        'liste_produits' => 'array',
	    'shop_order' => 'array',
	    'facture_commissions' => 'array',
        'fonds' => 'array',
        'taille_lots' => 'array',
	    'reseaux_sociaux' => 'array'
    ];

    protected $hidden = [
	    'updated_at', 'deleted_at','addresse_entreprise_contact_id',
	    'contact_entreprise_id', 'abonnement_id', 'type_activite_id',
	    'type_entreprise_id', 'ville_id'
    ];

	protected $dates = ['deleted_at', 'date_abonnement'];

	/**
	 * Recuperation des informations d'abonnement liées a l'entreprise
	 * @return $this
	 */
	public function abonnement()
	{
		return $this->belongsTo('App\Abonnement', 'abonnement_id','id')->select('id',
			'nom','nb_max_etageres','nb_max_rayons',
			'nb_max_sous_rayons','nb_max_produits','nb_max_tete_de_gondole',
			'nb_max_code_promo','prix');
	}

	/**
	 * Recuperation du type d'activité de l'entreprise
	 * @return $this
	 */
	public function typeActivite()
	{
		return $this->belongsTo('App\TypeActivite', 'type_activite_id', 'id')
			->select('id','nom');
	}

	/**
	 * Recuperation du type de l'entreprise
	 * @return $this
	 */
	public function typeEntreprise()
	{
		return $this->belongsTo('App\TypeEntreprise', 'type_entreprise_id', 'id')
			->select('id','abreviation', 'nom');
	}

	/**
	 * Recuperation de la ville ou se trouve l'entreprise
	 * @return $this
	 */
	public function ville()
	{
		return $this->belongsTo('App\Ville', 'ville_id', 'id')
			->select('id','nom');
	}

	/**
	 * Recuperation des informations de connection liées a l'entreprise
	 * @return $this
	 */
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id')
			->select('id', 'user_type_id', 'email', 'status', 'refresh_token', 'api_token','card_brand', 'card_last_four');
	}

	/**
	 * Recuperation des informations de l'entreprise sous forme de contact
	 * @return $this
	 */
	public function addresseEntreprise()
	{
		return $this->belongsTo('App\Contact', 'addresse_entreprise_contact_id','id')
			->select('id', 'nom', 'prenom',
				'telephone', 'addresse', 'infos_addresse',
				'commune', 'code_postal', 'ville',
				'email', 'Coordonnées_GPS','addresse_fact',
				'infos_addresse_fact', 'commune_fact', 'code_postal_fact',
				'ville_fact', 'email_fact', 'Coordonnées_GPS_fact');
	}

	/**
	 * Recuperation du carnet de contacts des personnes liées a l'entreprise
	 * @return $this
	 */
	public function contactEntreprise()
	{
		return $this->belongsTo('App\Contact', 'contact_entreprise_id','id')
			->select('id', 'nom', 'prenom',
				'telephone', 'addresse', 'infos_addresse',
				'commune', 'code_postal', 'ville',
				'email', 'Coordonnées_GPS');
	}
}
