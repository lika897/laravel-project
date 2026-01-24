<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\v2\CategoryResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => [
                'amount' => $this->price,
                'currency' => 'UAH',
            ],
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),

            'images' => ImageResource::collection($this->whenLoaded('images')),


            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
