<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeProduit extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
	    'dependances_categories_produits', 'nom', '%_commission'
    ];

    protected $casts = [
	    'dependances_categories_produits' => 'array'
    ];

	protected $dates = ['deleted_at'];

	protected $hidden = [
		'created_at', 'updated_at', 'deleted_at',
		'dependances_familles_produits'
	];
}
