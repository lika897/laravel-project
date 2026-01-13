<?php

namespace App\Services;

use App\Enums\QueuesEnum;
use App\Jobs\Admin\Products\Export\SaveToS3Job;
use App\Jobs\Admin\Products\Export\WriteLocalFile;
use App\Models\Product;
use App\Models\User;
use App\Notifications\Admin\ProductsExportNotification;
use App\Services\Contracts\ProductsExportServiceContract;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class ProductsExportService implements ProductsExportServiceContract
{
    /**
     * @throws \Throwable
     */
    public function export(User $user): void
    {
        $userId = $user->id;
        $folder = "export/products/$userId/";
        $disk = 'private';


        Storage::disk($disk)->makeDirectory($folder);


        $products = Product::lazyById()->pluck('id')->chunk(25);

        $writeFilesBatch = Bus::batch(
            $products->values()->map(function ($chunk, $index) use ($folder, $disk) {
                return new WriteLocalFile(
                    $folder . "products-{$index}.csv",
                    $chunk->toArray(),
                    $disk
                );
            })
        )->onQueue(QueuesEnum::ExportWriteLocal->value)
            ->catch(function (\Throwable $e) use ($userId) {
                logs()->error('[ProductsExportService] Batch failed: ' . $e->getMessage(), [
                    'user_id' => $userId,
                ]);
            })
            ->then(function () use ($folder, $disk, $user) {

                SaveToS3Job::dispatch($folder, $disk);
                $user->notify(new ProductsExportNotification(
                    csvFile: $folder . 'products.csv'
                ));
            })
            ->onQueue(QueuesEnum::ProductsExport->value)
            ->dispatch();
    }


}
