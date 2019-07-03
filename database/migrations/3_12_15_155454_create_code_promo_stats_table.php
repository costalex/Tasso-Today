<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodePromoStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_promo_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('entreprise_id');
            $table->unsignedInteger('groupe_id');
            $table->string('label_promo')->nullable(false)->unique();
            $table->string('code_promo')->nullable(false);
            $table->integer('%_reduction');
            $table->integer('nb_utilisations');
            $table->integer('nb_max_utilisations');
            $table->date('date_limite');
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
        Schema::dropIfExists('code_promo_stats');
    }
}
