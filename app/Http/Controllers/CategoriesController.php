<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }



    public function show(Category $category)
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }


    public function productsByCategory($id)
    {
        $category = Category::with('products.images')->findOrFail($id);

        $products = $category->products->map(function($product){
            $product->thumbnail_url = $product->thumbnailUrl;
            return $product;
        });

        return response()->json($category->products);
    }





}
