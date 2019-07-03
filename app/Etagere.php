<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etagere extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'entreprise_id', 'fond_id', 'rayon_id',
	    'sous_rayon_id', 'nom', 'type',
	    'list_produits'
    ];

    protected $casts = [
        'list_produits' => 'array'
    ];

    protected $hidden = [
	    'created_at', 'updated_at', 'deleted_at',
	    'fond_id', 'rayon_id', 'sous_rayon_id',
	    'entreprise_id'
    ];

	protected $dates = ['deleted_at'];

	/**
	 * Recuperation du fond ecran lié a l'etagere
	 * @return $this
	 */
	public function fondEcran()
	{
		return $this->belongsTo('App\FondEcran', 'fond_id', 'id')->select('id','label', 'path_file_image', 'positions_produits');
	}

	/**
	 * Recuperation du rayon dont l'etagere herite
	 * @return $this
	 */
	public function rayon()
	{
		return $this->belongsTo('App\Rayon', 'rayon_id', 'id')->select('id','nom');
	}

	/**
	 * Recuperation du sous-rayon dont l'etagere herite
	 * @return $this
	 */
	public function sous_rayon()
	{
		return $this->belongsTo('App\SousRayon', 'sous_rayon_id', 'id')->select('id','nom');
	}
}
