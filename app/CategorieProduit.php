<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategorieProduit extends Model
{
	use SoftDeletes;

	protected $guarded = [
		'id'
	];

	protected $fillable = [
		'dependances_familles_produits', 'nom', '%_commission',
		'tailles'
	];

	protected $casts = [
		'dependances_familles_produits' => 'array',
		'tailles' => 'array'
	];

	protected $dates = ['deleted_at'];

	protected $hidden = [
		'created_at', 'updated_at', 'deleted_at',
		'dependances_familles_produits'
	];
}
