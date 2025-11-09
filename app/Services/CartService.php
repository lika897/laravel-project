<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Contracts\CartContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartService implements Contracts\CartContract
{
    protected ?Collection $items {
        set(\Illuminate\Support\Collection|null $value) {
            if (!$value) {
                Session::forget('cart');
            } else {
                Session::put('cart', $value);
            }
        }
        get {
            return Session::get('cart', collect());
        }
    }
    public function add(Product $product, int $quantity = 1): void
    {
        if (!$this->items->where('id', $product->id)->isEmpty()) {
            $this->items = $this->items->map(fn ($item) => $item['id'] === $product->id
                ? $this->updateItemQty($item, ($item['quantity'] + $quantity))
                : $item
            );
        } else {
            $this->items = $this->items->add([
                'uuid' => Str::uuid()->toString(),
                'id' => $product->id,
                'quantity' => $quantity,
                'title' => $product->title,
                'slug' => $product->slug,
                'price' => $product->finalPrice,
                'subTotal' => round($quantity * $product->finalPrice),
                'thumbnailUrl' => $product->thumbnailUrl,

            ]);
        }
    }


    public function remove(string $uuid): void
    {
        $this->items = $this->items->filter(fn($item) => $item['uuid'] !== $uuid);
    }

    public function update(string $uuid, int $quantity): void
    {
        $this->items = $this->items
            ->map(fn ($item) => $item['uuid'] === $uuid
            ? $this->updateItemQty($item, $quantity)
            : $item);
    }

    public function all(): Collection
    {
        return $this->items;
//        return $this->items->map(fn($item) => (object) $item);
    }

    public function subTotal(): float
    {
        return round(
            $this->items->sum('subTotal')
        );
    }

    public function tax(): float
    {
        return round(
            $this->subTotal() * config('cart.tax') / 100
        );
    }

    public function total(): float
    {
        return round(
            $this->subTotal() + $this->tax()
        );
    }
    public function clear(): void
    {
        $this->items = null;
    }



    public function count(): int
    {
        return $this->items->sum('quantity');
    }
    protected function updateItemQty(array $item, int $quantity): array
    {
        return [
            ...$item,
            'quantity' => $quantity,
            'subTotal' => round($quantity * $item['price']),
            'slug' => $item['slug'],
            'thumbnailUrl' => $item['thumbnailUrl'] ?? null,
        ];

    }

}



