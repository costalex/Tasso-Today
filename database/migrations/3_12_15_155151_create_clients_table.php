<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
	        $table->increments('id');
	        $table->unsignedInteger('user_id')->unique();
            $table->json('list_groupe_id');
            $table->json('token_paiement')->nullable();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
	        $table->unsignedInteger('addresse_facturation');
	        $table->json('addresses_livraison');
            $table->string('telephone')->nullable();
            $table->json('list_entreprise_favoris')->nullable();
            $table->json('paniers')->nullable();
            $table->json('liste_paniers_historique')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
