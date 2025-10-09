<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     * @param Product $product
     * @param $image
     */
    public function deleted(Product $product): void
    {
//        Storage::delete($product->thumbnail);
//        $product->images()->each(fn ($image) = $image->delete());
//        Storage::deleteDirectory($product->imagesFolderPath());
        $disk = Storage::disk('public');


        if ($product->thumbnail && $disk->exists($product->thumbnail)) {
            $disk->delete($product->thumbnail);
        }

        foreach ($product->images as $image) {
            if ($disk->exists($image->path)) {
                $disk->delete($image->path);
            }
            $image->delete();
        }

        $thumbnailDir = dirname($product->thumbnail);
        if ($disk->exists($thumbnailDir) && count($disk->allFiles($thumbnailDir)) === 0) {
            $disk->deleteDirectory($thumbnailDir);
        }

        $firstImage = $product->images->first();
        if ($firstImage) {
            $galleryDir = dirname($firstImage->path);
            if ($disk->exists($galleryDir) && count($disk->allFiles($galleryDir)) === 0) {
                $disk->deleteDirectory($galleryDir);
            }
        }

    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
