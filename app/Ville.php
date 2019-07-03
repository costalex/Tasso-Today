<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ville extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'nom'
    ];

	protected $dates = ['deleted_at'];
}
