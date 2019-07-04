<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Entreprise;
use App\FamilleProduit;
use App\FondEcran;
use App\Produit;

class UpdateImagesUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:update-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update images url in database with config app.url';

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
        $this->updateCompaniesUrl();
        $this->updateProductFamilliesUrl();
        $this->updateWallpapersUrl();
        $this->updateProductsUrl();
    }

    private function updateCompaniesUrl()
    {
        $compagnies = Entreprise::all();

        foreach ($compagnies as $compagny) {
            $banniereImagePath = $this->getImagePathFromUrl($compagny->banniere);
            $newBanniereImageUrl = config('app.url') . $banniereImagePath;

            $logoImagePath = $this->getImagePathFromUrl($compagny->path_file_logo_entreprise);
            $newLogoImageUrl = config('app.url') . $logoImagePath;

            $compagny->banniere = $newBanniereImageUrl;
            $compagny->path_file_logo_entreprise = $newLogoImageUrl;
            $compagny->save();
        }
    }

    private function updateProductFamilliesUrl()
    {
        $productFamillies = FamilleProduit::all();

        foreach ($productFamillies as $familly) {
            $imagePath = $this->getImagePathFromUrl($familly->img_path);
            $newImageUrl = config('app.url') . $imagePath;

            $familly->img_path = $newImageUrl;
            $familly->save();
        }
    }

    private function updateWallpapersUrl()
    {
        $wallpapers = FondEcran::all();

        foreach ($wallpapers as $wallpaper) {
            $imagePath = $this->getImagePathFromUrl($wallpaper->path_file_image);
            $newImageUrl = config('app.url') . $imagePath;

            $wallpaper->path_file_image = $newImageUrl;
            $wallpaper->save();
        }
    }

    private function updateProductsUrl()
    {
        $products = Produit::all();

        foreach ($products as $product) {
            $mainPicture = $product->path_file_photo_principale;
            $secondaryPictures = $product->path_file_photos_secondaire;

            $newMainPicture = [];
            $newMainPicture[] =
                $this->getProductImageWithNewUrl($mainPicture[0]['image'], $mainPicture[0]['image_miniature']);

            $newSecondaryPrictures = [];
            if (!empty($secondaryPictures)) {
                foreach ($secondaryPictures as $picture) {
                    if (!empty($picture['image']) && !empty($picture['image_miniature'])) {
                        $newSecondaryPrictures[] =
                            $this->getProductImageWithNewUrl($picture['image'], $picture['image_miniature']);
                    }
                }
            }

            if (empty($newSecondaryPrictures)) {
                $newSecondaryPrictures = '';
            }

            $product->path_file_photo_principale = $newMainPicture;
            $product->path_file_photos_secondaire = $newSecondaryPrictures;
            $product->save();
        }
    }

    private function getProductImageWithNewUrl($image, $thumbnailImages)
    {
        $productImage = [];

        $mainPictureImagePath = $this->getImagePathFromUrl($image);
        $productImage['image'] = config('app.url') . $mainPictureImagePath;

        $newThumbnailImages = [];
        foreach ($thumbnailImages as $thumbailImage) {
            $thumbailImagePath = $this->getImagePathFromUrl($thumbailImage);
            $newThumbnailImages[] = config('app.url') . $thumbailImagePath;
        }
        $productImage['image_miniature'] = $newThumbnailImages;

        return $productImage;
    }

    private function getImagePathFromUrl($url)
    {
        $imagePath = '/storage/' . explode('/storage/', $url)[1];

        return $imagePath;
    }
}
