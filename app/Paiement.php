<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;

class Paiement extends Model
{
	use SoftDeletes;
	use Billable;

	protected $guarded = [
      'id'
    ];

    protected $fillable = [
        'charge_id', 'paiement_infos'
    ];

    protected $casts = [
        'paiement_infos' => 'array'
    ];

	protected $dates = ['deleted_at'];
}
