<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::orderByDesc('id')->limit(5)->get();
        $products = Product::orderByDesc('id')->limit(8)->get();

        return view('home', compact('categories', 'products'));
    }

    public function show(Product $product, Category $category)
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }

}
