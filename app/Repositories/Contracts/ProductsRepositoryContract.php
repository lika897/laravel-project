<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;
use App\Models\Product;

interface ProductsRepositoryContract
{
    public function store(Request $request): Product|false;

    public function update($request, Product $product): Product;
}
