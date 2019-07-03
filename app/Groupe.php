<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groupe extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'label', 'code_groupe'
    ];

	protected $dates = ['deleted_at'];

	protected $hidden = ['code_groupe'];
}
