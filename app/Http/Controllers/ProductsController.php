<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products =Product::orderByDesc('id')->paginate(12);

        return view('products.index', compact('products'));
    }
    public function show(Product $product)
    {
        $product->load(['categories', 'images']);

        $gallery = [
            $product->thumbnailUrl,
            ...$product->images->map(fn($image) => $image->url),
        ];
//        dd($product->images->pluck('path'));


        return view('products.show', compact('product', 'gallery'));
    }
}
