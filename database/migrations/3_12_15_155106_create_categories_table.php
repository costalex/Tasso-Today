<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorie_produits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable(false);
	        $table->string('dependances_familles_produits')->nullable(false);
	        $table->json('tailles');
	        $table->double('%_commission',8,2);
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
        Schema::dropIfExists('categorie_produits');
    }
}
