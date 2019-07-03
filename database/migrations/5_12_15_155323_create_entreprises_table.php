<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntreprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entreprises', function (Blueprint $table) {
	        $table->increments('id');
	        $table->unsignedInteger('user_id');
            $table->unsignedInteger('abonnement_id');
            $table->unsignedInteger('type_activite_id');
            $table->unsignedInteger('type_entreprise_id');
	        $table->enum('status', ['OUVERT', 'FERME', 'FERME_J', 'ARRET'])
		        ->default('FERME');
            $table->string('nom_enseigne')->nullable(false);
            $table->unsignedInteger('addresse_entreprise_contact_id');
            $table->unsignedInteger('contact_entreprise_id');
            $table->string('description')->nullable(true);
	        $table->string('siret')->nullable(false);
	        $table->json('Coordonnées_GPS');
            $table->unsignedInteger('ville_id');
            $table->json('horraires_ouverture');
            $table->string('banniere');
            $table->string('path_file_logo_entreprise')->nullable(false);
            $table->json('liste_produits');
            $table->json('shop_order');
            $table->json('facture_commissions');
            $table->json('fonds');
            $table->json('taille_lots');
            $table->double('panier_min_general',8,2)->default(0.00);
	        $table->json('reseaux_sociaux');
	        $table->timestamp('date_abonnement', 0)->default(Carbon::now())->nullable();
//            $table->date('date_abonnement')->default(Carbon::now());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entreprises');
    }
}
