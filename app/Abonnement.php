<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abonnement extends Model
{
	use SoftDeletes;

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'nom', 'nb_max_etageres', 'nb_max_rayons',
	    'nb_max_sous_rayons', 'nb_max_produits', 'nb_max_tete_de_gondole',
        'nb_max_code_promo', '%_commission', '%_reduction_commission',
	    'prix'
    ];

	protected $dates = ['deleted_at'];

	protected $hidden = [
		'created_at', 'updated_at', 'deleted_at'
	];
}
