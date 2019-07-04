<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

use App\Produit;

class DeleteUnusedProductsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:delete-unused';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete products images not used';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unusedImages = $this->getUnusedImagesNames();

        if ($this->confirm('Voulez-vous supprimer ' . count($unusedImages) . ' images inutilisées ?')) {
            foreach ($unusedImages as $image) {
                unlink(storage_path('app/public/images_produits/' . $image));
            }
        } else {
            $this->info('Suppression des images annulée');
        }
    }

    private function getUnusedImagesNames()
    {
        $productsImages = $this->getProductsImagesNames();

        $dir = new \DirectoryIterator('storage/app/public/images_produits/');

        $unusedImages = [];
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && !$this->isImageUsed($productsImages, $fileinfo->getFilename())) {
                $unusedImages[] = $fileinfo->getFilename();
            }
        }

        return $unusedImages;
    }

    private function getProductsImagesNames()
    {
        $products = Produit::all();
        $productsImages = [];

        foreach ($products as $product) {
            $mainPicture = $product->path_file_photo_principale;

            if (!empty($mainPicture[0]['image'])) {
                $productsImages[] = $this->getImageNameFromUrl($mainPicture[0]['image']);
            }

            foreach ($mainPicture[0]['image_miniature'] as $thumbailImage) {
                if (!empty($thumbailImage)) {
                    $productsImages[] = $this->getImageNameFromUrl($thumbailImage);
                }
            }

            $secondaryPictures = $product->path_file_photos_secondaire;

            if (!empty($secondaryPictures)) {
                foreach ($secondaryPictures as $picture) {
                    if (!empty($picture['image'])) {
                        $productsImages[] = $this->getImageNameFromUrl($picture['image']);
                    }

                    foreach ($picture['image_miniature'] as $thumbailImage) {
                        if (!empty($thumbailImage)) {
                            $productsImages[] = $this->getImageNameFromUrl($thumbailImage);
                        }
                    }
                }
            }
        }

        return $productsImages;
    }

    private function getImageNameFromUrl($url)
    {
        $imageName = explode('/storage/images_produits/', $url)[1];

        return $imageName;
    }

    private function isImageUsed($productsImages, $image)
    {
        $isImageUsed = false;

        foreach ($productsImages as $dbImage) {
            if ($dbImage === $image) {
                $isImageUsed = true;
                break;
            }
        }

        return $isImageUsed;
    }
}
