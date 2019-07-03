<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
	        $table->unsignedInteger('entreprise_id');
            $table->unsignedInteger('user_type_id');
            $table->unsignedInteger('paiement_id');
	        $table->string('addresse_livraison_client')->nullable();
	        $table->string('num_commande')->nullable(false)->unique();
            $table->json('bon_commande');
            $table->json('facture');
            $table->json('list_promo');
            $table->longText('commentaire_client')->nullable();
            $table->longText('commentaire_entreprise')->nullable();
	        $table->enum('statut', ['EN_ATTENTE', 'ACCEPTER', 'EN_COURS', 'TERMINE', 'ANNULE'])
		        ->default('EN_ATTENTE');
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
        Schema::dropIfExists('factures');
    }
}
