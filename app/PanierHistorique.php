<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanierHistorique extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'list_produits', 'prix_total'
    ];

    protected $casts = [
      'list_produits' => 'array'
    ];

	protected $dates = ['deleted_at'];
}
