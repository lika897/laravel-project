<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Contracts\CartContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartService implements CartContract
{


    protected function getItems(): Collection
    {
        return Session::get('cart', collect());
    }


    protected function setItems(Collection $items): void
    {
        Session::put('cart', $items);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $items = $this->getItems();

        $existingItem = $items->firstWhere('id', $product->id);

        if ($existingItem) {
            $items = $items->map(fn ($item) => $item['id'] === $product->id
                ? $this->updateItemQty($item, $item['quantity'] + $quantity)
                : $item
            );
        } else {
            $items->push([
                'uuid' => Str::uuid()->toString(),
                'id' => $product->id,
                'quantity' => $quantity,
                'title' => $product->title,
                'slug' => $product->slug,
                'price' => $product->finalPrice,
                'subTotal' => round($quantity * $product->finalPrice),
                'thumbnailUrl' => $product->thumbnailUrl,
                'sku' => $product->SKU,
            ]
            );
        }

        $this->setItems($items);
    }

    public function remove(string $uuid): void
    {
        $items = $this->getItems()->filter(fn ($item) => $item['uuid'] !== $uuid)->values();
        $this->setItems($items);
    }

    public function update(string $uuid, int $quantity): void
    {
        $items = $this->getItems()->map(fn ($item) => $item['uuid'] === $uuid
            ? $this->updateItemQty($item, $quantity)
            : $item
        )->values();
        $this->setItems($items);
    }

    public function all(): Collection
    {
        return $this->getItems();
    }

    public function subTotal(): float
    {
        return round($this->getItems()->sum('subTotal'));
    }

    public function tax(): float
    {
        return round($this->subTotal() * config('cart.tax', 0) / 100);
    }

    public function total(): float
    {
        return round($this->subTotal() + $this->tax());
    }

    public function clear(): void
    {
        $this->setItems(collect());
    }

    public function count(): int
    {
        return $this->getItems()->sum('quantity');
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
