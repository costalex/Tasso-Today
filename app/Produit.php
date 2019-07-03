<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
	use SoftDeletes;

	protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'nom', 'description', 'famille_id',
        'categorie_id', 'type_id', 'marque_id',
	    'entreprise_id', 'poids', 'status', 'path_file_photo_principale',
        'path_file_photos_secondaire', 'longueur', 'largeur',
        'hauteur', 'volume', 'ref_produit',
        'ref_produit'
    ];

    protected $casts = [
        'path_file_photo_principale' => 'array',
        'path_file_photos_secondaire' => 'array'
    ];

	protected $dates = ['deleted_at'];

	protected $hidden = [
		'created_at', 'deleted_at',
		'famille_id', 'categorie_id', 'type_id',
		'marque_id'
	];

	/**
	 * Recuperation de la famille dont le produit herite
	 * @return $this
	 */
    public function famille()
    {
    	return $this->belongsTo('App\FamilleProduit', 'famille_id', 'id')->select('id','nom');
    }

	/**
	 * Recuperation de la categorie dont le produit herite
	 * @return $this
	 */
	public function categorie()
	{
		return $this->belongsTo('App\CategorieProduit', 'categorie_id', 'id')->select('id','dependances_familles_produits', 'nom', 'tailles');
	}

	/**
	 * Recuperation du type dont le produit peut herite
	 * @return $this
	 */
	public function type()
	{
		return $this->belongsTo('App\TypeProduit', 'type_id', 'id')->select('id','dependances_categories_produits', 'nom');
	}

	/**
	 * Recuperation de la marque du produit
	 * @return $this
	 */
	public function marque()
	{
		return $this->belongsTo('App\MarqueProduit', 'marque_id', 'id')->select('id','nom');
	}

	/**
	 * Recuperation des informations de l'entreprise ayant privatisé le produit sinon id=0
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
}
