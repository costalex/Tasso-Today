<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtageresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etageres', function (Blueprint $table) {
            $table->increments('id');
	        $table->unsignedInteger('entreprise_id');
	        $table->unsignedInteger('fond_id')->default(1);
            $table->unsignedInteger('rayon_id');
            $table->unsignedInteger('sous_rayon_id');
	        $table->string('nom')->default('Etagere');
	        $table->enum('type', ['PERSSO', 'PROMO'])->default('PERSSO');
            $table->json('list_produits');
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
        Schema::dropIfExists('etageres');
    }
}
