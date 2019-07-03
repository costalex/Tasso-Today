<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Couleur extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'nom', 'code_hexa'
    ];

	protected $dates = ['deleted_at'];

	protected $hidden = [
		'updated_at', 'created_at', 'deleted_at',
		'id'
	];
}
