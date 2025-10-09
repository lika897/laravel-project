<?php

namespace App\Models;


use App\Observers\ProductObserver;
use App\Services\Contracts\FileServiceContract;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'SKU',
        'description',
        'price',
        'discount',
        'quantity',
        'thumbnail',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::get(function (){
            return Storage::url($this->thumbnail);
        });
    }

    public function thumbnail(): Attribute
    {
        return Attribute::make(
            set: function (UploadedFile|string $file) {
                if (is_string($file)) {
                    return $file;
                } else {
                    if (!empty($this->attributes['thumbnail'])) {
                        Storage::delete($this->attributes['thumbnail']);
                    }
                    $path = 'products/' . $this->attributes['slug'];
                    return app(FileServiceContract::class)->upload($file, $path);
                }
            }
        );
    }
}
