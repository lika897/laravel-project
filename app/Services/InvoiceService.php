<?php

namespace App\Services;

use App\Models\Order;
use App\Services\Contracts\InvoiceServiceContract;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice;

class InvoiceService implements Contracts\InvoiceServiceContract
{

    public function generate(Order $order): Invoice
    {
        $order->loadMissing(['transaction', 'products']);

        $customer = new Buyer([
            'name' => "$order->name $order->surname",
            'phone' => $order->phone,
            'custom_fields' => [
                'email' => $order->email,
                'address' => $order->address,
                'city' => $order->city
            ]
        ]);

        $items = [];

        foreach ($order->products as $product){
            $items[] = InvoiceItem::make($product->pivot->title)->quantity($product->quantity)->pricePerUnit($product->pivot->single_price)->units('qty');
        }

        return Invoice::make()->buyer($customer)->status($order->status->value)->filename($order->vendor_order_id)->taxRate(config('cart.tax'))->addItems($items);

    }
}
