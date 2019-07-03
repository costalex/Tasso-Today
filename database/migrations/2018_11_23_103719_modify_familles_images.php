<?php

use Illuminate\Database\Migrations\Migration;

use App\FamilleProduit;

class ModifyFamillesImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $newImagesPath = [
             config('app.url') . '/storage/bobby_images/familles_images/alimentaire.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/animalerie.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/Auto-Moto.png',
             config('app.url') . '/storage/bobby_images/familles_images/bebe.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/bureau.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/electronique.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/beaute.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/jardin.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/loisirs.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/maison.jpg',
             config('app.url') . '/storage/bobby_images/familles_images/mode.jpg'
        ];

        for ($i = 1; $i < 12; $i++) {
            $famille = FamilleProduit::find($i);

            $famille->img_path = $newImagesPath[$i - 1];
            $famille->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $oldImagesPath = [
            config('app.url') . '/storage/bobby_images/familles_images/Alimentaire.png',
            config('app.url') . '/storage/bobby_images/familles_images/Animalerie.png',
            config('app.url') . '/storage/bobby_images/familles_images/Auto-Moto.png',
            config('app.url') . '/storage/bobby_images/familles_images/Bébé et Puériculture.png',
            config('app.url') . '/storage/bobby_images/familles_images/Bureautique.png',
            config('app.url') . '/storage/bobby_images/familles_images/High-Tech.png',
            config('app.url') . '/storage/bobby_images/familles_images/Hygiène et santé.png',
            config('app.url') . '/storage/bobby_images/familles_images/Jardin.png',
            config('app.url') . '/storage/bobby_images/familles_images/Loisirs.png',
            config('app.url') . '/storage/bobby_images/familles_images/Maison.png',
            config('app.url') . '/storage/bobby_images/familles_images/Mode.png'
        ];

        for ($i = 1; $i < 12; $i++) {
            $famille = FamilleProduit::find($i);

            $famille->img_path = $oldImagesPath[$i - 1];
            $famille->save();
        }
    }
}
