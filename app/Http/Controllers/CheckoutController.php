<?php

namespace App\Http\Controllers;


use App\Facades\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::all();

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('warning', 'Your cart is empty.');
        }

        return view('checkout.index', compact('user', 'cart'));
    }
}
