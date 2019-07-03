<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	protected $guarded = [
		'id'
	];

    protected $fillable = [
    	'user_id', 'list_groupe_id', 'token_paiement',
        'nom', 'prenom', 'addresse_facturation',
	    'addresses_livraison', 'telephone', 'list_entreprise_favoris',
	    'paniers', 'liste_paniers_historique'
    ];

    protected $casts = [
        'list_groupe_id' => 'array',
	    'token_paiement' => 'array',
	    'addresses_livraison' => 'array',
        'list_entreprise_favoris' => 'array',
        'paniers' => 'array',
        'liste_paniers_historique' => 'array',
    ];

	protected $dates = ['deleted_at'];

	protected $hidden = [
		'token_paiement', 'updated_at', 'deleted_at',
		'user_id', 'addresse_facturation'
	];

	/**
	 * Recuperation des informations de connection pour le client
	 * @return $this
	 */
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id')
			->select('id', 'user_type_id', 'email', 'status', 'api_token', 'card_brand', 'card_last_four');
	}

	/**
	 * Recuperation de l'addresse de facturation creer a l'aide de la table 'Contact'
	 * @return $this
	 */
	public function contacts_facturation()
	{
		return $this->belongsTo('App\Contact', 'addresse_facturation', 'id');
	}

	public function userPaiementInfos()
	{
		return $this->belongsTo('App\User', 'user_id', 'id')
			->select('id', 'email', 'user_type_id','status','stripe_id','card_brand','card_last_four','trial_ends_at');
	}
}
