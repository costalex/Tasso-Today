<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarqueProduit extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'nom'
    ];

	protected $dates = ['deleted_at'];

	protected $hidden = [
		'created_at', 'updated_at', 'deleted_at'
	];
}
