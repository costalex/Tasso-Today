<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
	use HasApiTokens, Notifiable, Billable;

	protected $guarded = [
		'id'
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type_id', 'email', 'password',
	    'password_caisse', 'status', 'refresh_token',
	    'remember_token', 'api_token', 'confirm_code',
	    'stripe_id', 'card_brand', 'card_last_four',
	    'trial_ends_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'confirm_code', 'created_at',
	    'updated_at', 'deleted_at'
    ];

	protected $dates = ['deleted_at'];

	/**
	 * Recuperation du role de l'utilisateur dans ses informations de connection
	 * @return $this
	 */
	public function userType()
	{
		return $this->belongsTo('App\UserType', 'user_type_id', 'id')
			->select('id', 'nom');
	}

	/**
	 * Verification du role de l'utilisateur en tant qu'admin
	 * @return bool
	 */
	public function isAdmin()
	{
		return $this->userType->nom == "Admin";
	}

	/**
	 * Verification du role de l'utilisateur en tant qu'un client
	 * @return bool
	 */
	public function isClient()
	{
		return $this->userType->nom == "Client";
	}

	/**
	 * Verification du role de l'utilisateur en tant qu'un vendeur(entreprise, magasin)
	 * @return bool
	 */
	public function isEntreprise()
	{
		return $this->userType->nom == "Entreprise";
	}
}