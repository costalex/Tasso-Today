<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFondEcransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fond_ecrans', function (Blueprint $table) {
            $table->increments('id');
	        $table->unsignedInteger('event_id');
	        $table->string('label')->nullable(false);
            $table->string('path_file_image')->nullable(false);
            $table->json('positions_produits');
	        $table->double('prix',8,2)->default(0.00);
	        $table->boolean('activated')->default(false);
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
        Schema::dropIfExists('fond_ecrans');
    }
}
