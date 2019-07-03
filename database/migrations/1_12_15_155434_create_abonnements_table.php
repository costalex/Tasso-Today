<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbonnementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable(false);
            $table->integer('nb_max_etageres');
	        $table->integer('nb_max_rayons');
	        $table->integer('nb_max_sous_rayons');
	        $table->integer('nb_max_produits');
            $table->integer('nb_max_tete_de_gondole');
            $table->integer('nb_max_code_promo');
            $table->double('%_commission',8,2);
	        $table->double('%_reduction_commission',8,2);
	        $table->double('prix',8,2);
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
        Schema::dropIfExists('abonnements');
    }
}
