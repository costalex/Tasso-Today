<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilleProduit extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'nom', '%_commission', 'img_path'
    ];

	protected $dates = ['deleted_at'];

	protected $hidden = [
		'created_at', 'updated_at', 'deleted_at'
	];
}
