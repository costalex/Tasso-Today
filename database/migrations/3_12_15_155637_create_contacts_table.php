<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('telephone')->nullable();

	        $table->string('addresse')->nullable();
            $table->string('infos_addresse')->nullable();
	        $table->string('commune')->nullable();
	        $table->string('code_postal')->nullable();
	        $table->string('ville')->nullable();
	        $table->string('email')->nullable();
	        $table->json('Coordonnées_GPS')->nullable();


	        $table->string('addresse_fact')->nullable();
	        $table->string('infos_addresse_fact')->nullable();
	        $table->string('commune_fact')->nullable();
	        $table->string('code_postal_fact')->nullable();
	        $table->string('ville_fact')->nullable();
            $table->string('email_fact')->nullable();
	        $table->json('Coordonnées_GPS_fact')->nullable();

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
        Schema::dropIfExists('contacts');
    }
}
