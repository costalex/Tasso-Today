<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSousRayonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sous_rayons', function (Blueprint $table) {
            $table->increments('id');
	        $table->unsignedInteger('entreprise_id');
	        $table->unsignedInteger('rayon_id');
	        $table->string('nom')->default('Sous-rayon');
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
        Schema::dropIfExists('sous_rayons');
    }
}
