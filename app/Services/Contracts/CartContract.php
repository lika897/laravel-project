<?php

namespace App\Services\Contracts;

use App\Models\Product;
use Illuminate\Support\Collection;

interface CartContract
{
    public function add(Product $product, int $quantity = 1): void;
    public function remove(string $uuid): void;
    public function update(string $uuid, int $quantity):void;
    public function all(): Collection;

    public function count(): int;

    public function clear(): void;
    public function subTotal(): float;
    public function tax(): float;
    public function total(): float;

}
