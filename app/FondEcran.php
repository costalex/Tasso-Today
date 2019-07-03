<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FondEcran extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'event_id', 'label', 'path_file_image',
	    'positions_produits', 'prix', 'activated'
    ];

    protected $casts = [
        'positions_produits' => 'array'
    ];

    protected $hidden = [
        'id', 'event_id'
    ];

	protected $dates = ['deleted_at'];

	/**
	 * Recuperation de l'evenement lié au font d'ecran si il en dispose d'un
	 * @return $this
	 */
	public function event()
	{
		return $this->belongsTo('App\Event', 'event_id','id')->select('id', 'nom');
	}
}
