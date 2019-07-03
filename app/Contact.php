<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	protected $guarded = [
		'id'
	];

	protected $fillable = [
		'nom', 'prenom', 'telephone',
		'addresse', 'infos_addresse', 'commune',
		'code_postal', 'ville', 'email',
		'Coordonnées_GPS', 'addresse_fact', 'infos_addresse_fact',
		'commune_fact', 'code_postal_fact', 'ville_fact',
		'email_fact', 'Coordonnées_GPS_fact'
	];

	protected $casts =[
		'Coordonnées_GPS' => 'array',
		'Coordonnées_GPS_fact' => 'array'
	];

	protected $hidden = [
		'created_at', 'updated_at', 'deleted_at'
	];
}
