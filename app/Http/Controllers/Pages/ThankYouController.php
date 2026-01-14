<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $vendorOrderId)
    {
        try {
            $order = Order::with(['transaction', 'products'])->where('vendor_order_id', $vendorOrderId)->firstOnFail();
            $showDetails = $order?->user_id && auth()->check() && $order->user_id === auth()->id();

            return view('orders/thank-you', compact('order', 'showDetails'));
        } catch (\Throwable $exception){
            logs()->error('[ThankYouController]' . $exception->getMessage(), [
                'exception' => $exception,
                'vendor_order_id' => $vendorOrderId,
                'user_id'=> auth()-> id(),
            ]);

            return redirect()->route('home');
        }
    }
}
