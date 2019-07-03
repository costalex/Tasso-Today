<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SousRayon extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'nom', 'entreprise_id', 'rayon_id'
    ];

	protected $hidden = [
		'created_at', 'updated_at', 'deleted_at',
		'entreprise_id', 'rayon_id'
	];

	protected $dates = ['deleted_at'];

	/**
	 * Recuperation du rayon dont le sous-rayon herite
	 * @return $this
	 */
	public function rayon()
	{
		return $this->belongsTo('App\Rayon', 'rayon_id', 'id')->select('id','nom');
	}

}
