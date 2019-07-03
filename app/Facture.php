<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facture extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'client_id', 'entreprise_id', 'user_type_id',
        'paiement_id', 'addresse_livraison_client', 'num_commande',
	    'bon_commande', 'facture', 'list_promo',
	    'commentaire_client', 'commentaire_entreprise', 'statut'
    ];

    protected $casts = [
      'bon_commande' => 'array',
      'facture' => 'array',
      'list_promo' => 'array',
    ];

	protected $dates = [
		'created_at', 'updated_at', 'deleted_at'
	];

	protected $hidden = [
		'deleted_at'
	];

	/**
	 * Recuperation des informations client lié a la facture
	 * @return $this
	 */
	public function client()
	{
		return $this->belongsTo('App\Client', 'client_id', 'id')->select('id',
			'nom', 'prenom', 'addresse_facturation',
			'addresses_livraison','telephone'
			);
	}

	/**
	 * Recuperation des informations entreprise lié a la facture
	 * @return $this
	 */
	public function entreprise()
	{
		return $this->belongsTo('App\Entreprise', 'entreprise_id', 'id')->select(
			'id', 'nom_enseigne', 'type_activite_id',
			'type_entreprise_id', 'addresse_entreprise_contact_id', 'contact_entreprise_id',
			'description', 'siret', 'ville_id',
			'horraires_ouverture'
		)->with('ville', 'typeActivite', 'typeEntreprise', 'addresseEntreprise', 'contactEntreprise');
	}

	/**
	 * Recuperation du role de l'editeur de la facture
	 * @return $this
	 */
	public function userType()
	{
		return $this->belongsTo('App\UserType', 'user_type_id', 'id')->select('id','nom');
	}

	/**
	 * Recuperation des informations de paiement liées a la facture
	 * @return $this
	 */
	public function paiement()
	{
		return $this->belongsTo('App\Paiement', 'paiement_id', 'id')->select('id','token');
	}
}
