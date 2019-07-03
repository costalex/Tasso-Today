<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
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
