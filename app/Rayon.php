<?php

namespace App;

use function array_push;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rayon extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
	    'nom', 'entreprise_id'
    ];

    protected $hidden = [
	    'created_at', 'updated_at', 'deleted_at',
    	'entreprise_id'
    ];

	protected $dates = ['deleted_at'];
}
