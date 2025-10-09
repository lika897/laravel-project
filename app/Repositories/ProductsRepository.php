<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Repositories\Contracts\ProductsRepositoryContract;

class ProductsRepository implements ProductsRepositoryContract
{
    /**
     * @param Request $request
     * @return Product|false
     *
     * @throws \Throwable
     */

    public function store(Request $request): Product|false
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $this->formRequestData($request);

                $attributes = $data['attributes'];

                $attributes['slug'] = Str::slug($attributes['title']);

                $thumbnail = $attributes['thumbnail'] ?? null;
                unset($attributes['thumbnail']);

                $product = Product::create($attributes);

                if (!$product) {
                    return false;
                }

                if ($thumbnail instanceof \Illuminate\Http\UploadedFile) {
                    $fileService = app(\App\Services\Contracts\FileServiceContract::class);
                    $filePath = $fileService->upload($thumbnail, 'products/' . $attributes['slug']);
                    $product->thumbnail = $filePath;
                    $product->save();
                }

                $fileService = app(\App\Services\Contracts\FileServiceContract::class);

                if ($request->hasFile('images')) {
                    $this->uploadGalleryImages($product, $request->file('images'));
                }


                $this->updateProductRelations($product, $data);

                return $product;
            });
        } catch (\Throwable $throwable) {
            dd('ERROR:', $throwable->getMessage(), $throwable->getTraceAsString());

            return false;
        }
    }

    public function update($request, Product $product): Product
    {
        $oldSlug = $product->slug;
        $newSlug = Str::slug($request->title);

        $fileService = app(\App\Services\Contracts\FileServiceContract::class);
        $disk = Storage::disk('public');

        $product->update([
            'title' => $request->title,
            'SKU' => $request->SKU,
            'description' => $request->description,
            'price' => $request->price,
            'discount' => $request->discount,
            'quantity' => $request->quantity,
            'slug' => $newSlug,
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                $disk->delete($product->thumbnail);
            }

            $filePath = $fileService->upload($request->file('thumbnail'), 'products/' . $newSlug);
            $product->thumbnail = $filePath;
            $product->save();

            if ($oldSlug !== $newSlug) {
                $disk->deleteDirectory('products/' . $oldSlug);
            }
        } elseif ($oldSlug !== $newSlug && $product->thumbnail) {
            $oldPath = $product->thumbnail;
            $filename = basename($oldPath);
            $newPath = 'products/' . $newSlug . '/' . $filename;

            if ($disk->exists($oldPath)) {
                $disk->move($oldPath, $newPath);
                $product->thumbnail = $newPath;
                $product->save();
            }

            $disk->deleteDirectory('products/' . $oldSlug);
        }

        if ($request->hasFile('images')) {
            $this->uploadGalleryImages($product, $request->file('images'));
        }

        if ($request->has('categories')) {
            $product->categories()->sync($request->get('categories'));
        }

        return $product;
    }

    protected function uploadGalleryImages(Product $product, array $images): void
    {
        $fileService = app(\App\Services\Contracts\FileServiceContract::class);

        foreach ($images as $image) {
            if ($image->isValid()) {
                $filePath = $fileService->upload($image, 'products/gallery/' . $product->slug);

                $imageModel = new \App\Models\Image();
                $imageModel->path = $filePath;

                $product->images()->save($imageModel);
            }
        }
    }



    protected function formRequestData(Request $request): array
    {
        $attributes = Arr::except($request->validated(), ['categories', 'images']);

        $attributes['slug'] = Str::slug($attributes['title']);

        return [
            'attributes' => $attributes,
            'categories' => $request->get('categories', []),
//            'images' => $request->get('images', []),
        ];
    }

    protected function updateProductRelations(Product $product, array $data): void
    {
        $product->categories()->sync($data['categories']);

    }


}
