<?php

namespace App\Models;

use App\Services\Contracts\FileServiceContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $guarded = [
        'path',
        'imageable_type',
        'imageable_id',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }




//    public function setPathAttribute(array $pathData): void
//    {
//        /**
//         * @var \Illuminate\Http\UploadedFile $image
//         */
//        $image = $pathData['image'];
//        $this->attributes['path'] = app(FileServiceContract::class)->upload(
//            $image,
//            $pathData['path']
//        );
//
//
//    }

    public function url(): Attribute
    {
        return Attribute::get(function (){
            return Storage::url($this->path);
        });
    }

//    public function setPathAttribute(array $pathData): void
//    {
//        /**
//         * @var \Illuminate\Http\UploadedFile $image
//         */
//        $image = $pathData['image'];
//        $path = $pathData['path'];
//
//        $this->attributes['path'] = app(FileServiceContract::class)->upload($image, $path);
//    }



}
