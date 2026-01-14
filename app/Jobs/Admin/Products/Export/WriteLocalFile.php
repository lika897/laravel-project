<?php

namespace App\Jobs\Admin\Products\Export;

use App\Enums\QueuesEnum;
use App\Models\Product;
use FontLib\TrueType\Collection;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Storage;
use League\Csv\AbstractCsv;

class WriteLocalFile implements ShouldQueue
{
    use Batchable, Queueable;

    protected string $disk;

    public function __construct(
        protected string $fileName,
        protected array $productsIds = [],
        string $disk = 'local'
    ) {
        $this->disk = $disk;
    }

    public function middleware(): array
    {
        $key = "products-export:" . $this->fileName;
        return [
            new WithoutOverlapping($key, expiresAfter: 60)
        ];
    }

    public function handle(): void
    {
        if ($this->batch()?->canceled()) return;

        try {
            $filePath = Storage::disk($this->disk)->path($this->fileName);


            $csv = \League\Csv\Writer::createFromPath($filePath, 'w+');
            $csv->setDelimiter(';');

            Product::query()
                ->with(['images', 'categories'])
                ->whereIn('id', $this->productsIds)
                ->chunk(10, function ($products) use ($csv) {
                    $rows = $products->map(function (Product $product) {
                        $productData = $product->toArray();
                        $productData['thumbnail'] = $product->thumbnailUrl;

                        return [
                            ...$productData,
                            'categories' => $product->categories->pluck('title')->implode(', '),
                            'images' => $product->images->pluck('url')->implode(', '),
                        ];
                    });
                    $csv->insertAll($rows->toArray());
                });

            logs()->info('[WriteLocalFile] File created: ' . $filePath);

        } catch (\Throwable $e) {
            logs()->error('[WriteLocalFile] Failed: ' . $e->getMessage(), [
                'file' => $this->fileName,
                'productsIds' => $this->productsIds,
            ]);
            $this->batch()?->cancel();
        }
    }

}

