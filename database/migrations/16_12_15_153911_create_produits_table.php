<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable(false);
            $table->longText('description')->nullable(false);
            $table->unsignedInteger('famille_id')->nullable(false);
            $table->unsignedInteger('categorie_id')->nullable(false);
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('marque_id')->nullable(false);
            $table->unsignedInteger('entreprise_id')->default(0);
            $table->integer('poids')->nullable(false);
            $table->enum('status', ['PRIVE', 'PUBLIC'])->nullable(false);
            $table->json('path_file_photo_principale')->nullable(false);
            $table->json('path_file_photos_secondaire')->nullable(false);
            $table->double('longueur', 8,2)->nullable(false);
            $table->double('largeur', 8,2)->nullable(false);
            $table->double('hauteur', 8,2)->nullable(false);
            $table->double('volume', 8,2)->nullable(false);
            $table->string('ref_produit')->nullable(false)->unique();
            $table->enum('unite_mesure', ['KG','L', 'UNITE'])->nullable(false);//Voir si unitées a ajouter
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
        Schema::dropIfExists('produits');
    }
}
