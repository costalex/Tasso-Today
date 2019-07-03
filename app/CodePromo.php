<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodePromo extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'entreprise_id', 'groupe_id', 'panier_min_promo',
        'label_promo', 'code_promo', '%_reduction',
        'nb_utilisations','nb_max_utilisations', 'date_limite'
    ];

	protected $dates = ['deleted_at'];

	/**
	 * Recuperation des informations entreprise liées au code promo
	 * @return $this
	 */
    public function entreprise()
    {
    	return $this->belongsTo('App\Entreprise', 'entreprise_id', 'id')->select('id',
    		'abonnement_id', 'type_activite_id', 'type_entreprise_id',
		    'user_type_id', 'nom_enseigne');
    }

	/**
	 * Recuperation des informations concernant le groupe auxquelles s'appliquent le code promo
	 * @return $this
	 */
    public function groupe()
    {
    	return $this->belongsTo('App\Groupe', 'groupe_id', 'id')->select('id','label');
    }
}
