<?php

namespace App\Services;


use App\Services\Contracts\FileServiceContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService implements FileServiceContract
{

    public function upload(UploadedFile $file, string $path): string
    {
        $fileName = Str::slug(microtime()) . '-' . $file->getClientOriginalName();
//        $filePath = "$path/$fileName";
        $filePath = $file->storeAs($path, $fileName, 'public');


//        Storage::put($filePath, File::get($file));
        Storage::setVisibility($filePath, 'public');

        return $filePath;
    }
}
