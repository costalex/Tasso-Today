<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('entreprise_id');
            $table->unsignedInteger('ville_id');
	        $table->unsignedInteger('type_activite_id');
	        $table->longText('details')->nullable(false);
	        $table->enum('type', ['CREATION', 'SUPPRESSION', 'BUG', 'PRODUITS', 'PAIEMENT', 'RDV', 'NON_DEFINI'])
		        ->default('NON_DEFINI');
	        $table->enum('statut', ['EN_ATTENTE', 'ACCEPTER', 'REJETER', 'NON_DEFINI'])
		        ->default('NON_DEFINI');
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
        Schema::dropIfExists('demandes');
    }
}
