<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demande extends Model
{
	use SoftDeletes;

	protected $guarded = [
		'id'
	];

	protected $fillable = [
		'entreprise_id', 'ville_id', 'type_activite_id',
		'type', 'status', 'details'
	];

	protected $hidden = [
		'created_at', 'updated_at','deleted_at'
	];

	/**
	 * Recuperation des informations liées a l'entreprise qui a emis la demande
	 * @return $this
	 */
	public function entreprise()
	{
		return $this->belongsTo('App\Entreprise', 'entreprise_id', 'id')
			->select('user_id', 'abonnement_id', 'type_activite_id',
				'nom_enseigne', 'type_entreprise_id', 'status',
				'addresse_entreprise_contact_id', 'contact_entreprise_id', 'description',
				'siret', 'Coordonnées_GPS', 'ville_id',
				'horraires_ouverture', 'banniere', 'path_file_logo_entreprise',
				'liste_produits', 'list_etageres', 'facture_commissions',
				'fonds', 'taille_lots', 'panier_min_general');
	}

	/**
	 * Recuperation de la ville en fonction de son id liée a la demande
	 * @return $this
	 */
	public function ville()
	{
		return $this->belongsTo('App\Ville', 'ville_id', 'id')
			->select('id','nom');
	}

	/**
	 * Recuperation des informations liées au type d'activitées de l'entreprise ayant emis la demande
	 * @return $this
	 */
	public function typeActivite()
	{
		return $this->belongsTo('App\TypeActivite', 'type_activite_id', 'id')
			->select('id','nom');
	}



}
