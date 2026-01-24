<?php

namespace App\Http\Controllers;

use App\Facades\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::all();
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        Cart::add($product, $quantity);

        return back()->with([
            'success' => 'Product added to cart',
            'product_added_id' => $product->id,
            'thumbnailUrl' => $product->thumbnailUrl,
        ]);
    }

    public function update(Request $request, string $uuid)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::update($uuid, $request->input('quantity'));

        return back()->with('success', 'Cart updated successfully.');
    }


    public function remove(string $uuid)
    {
        Cart::remove($uuid);
        return back()->with('success', 'Product removed from cart');
    }

    public function clear()
    {
        Cart::clear();
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully');
    }











}
