<?php

namespace App\Jobs\Admin\Products\Export;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;
use Throwable;

class SaveToS3Job implements ShouldQueue
{
    use Queueable;

    public function __construct(protected string $folder, protected string $disk = 'private')
    {
        //
    }


    public function handle(): void
    {
        try {
            $csv = Writer::fromString();
//            $csv->setEscape('');
            $csv->setDelimiter(';');
            $csv->setEndOfLine("\n");

            $csv->insertOne([
                'id',
                'title',
                'slug',
                'SKU',
                'description',
                'price',
                'discount',
                'quantity',
                'thumbnail',
                'created_at',
                'updated_at',
                'categories',
                'images',
            ]);



//            $files = Storage::disk('local')->allFiles($this->folder);

            $files = Storage::disk($this->disk)->allFiles($this->folder);

            foreach ($files as $filePath){
//                $content = Storage::disk('local')->get($filePath);
                $content = Storage::disk($this->disk)->get($filePath);
                $reader = Reader::fromString($content)->setDelimiter(';');
                $csv->insertAll($reader->getRecords());
//                Storage::disk('local')->delete($filePath);
                Storage::disk($this->disk)->delete($filePath);
            }

//            Storage::disk('local')->put($this->folder . '/products.csv', $csv->toString());
            Storage::disk($this->disk)->put($this->folder . 'products.csv', $csv->toString());

        } catch (Throwable $throwable){
            logs()->error('[SaveToS3Job] Exception: ' . $throwable, [
                'exception' => $throwable,
                'folder' => $this->folder,
            ]);

            throw new \Exception('SaveToS3Job process was failed');
        }


    }
}
