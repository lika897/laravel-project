<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentSystemEnum;
use App\Enums\TransactionStatusEnum;
use App\Facades\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderRepositoryContracts;

class OrderRepository implements Contracts\OrderRepositoryContracts
{

    /**
     * @throws \Exception
     */
    public function create(array $data): Order|false
    {
        $data = [
            ...$data,
            'total' => Cart::total(),
            'status' => OrderStatusEnum::InProcess,
        ];

        $order = auth()->check()
        ? auth()->user()->orders()->create($data)
        : Order::create($data);

        $this->addProductsToOrder($order);

        return $order;
    }

    public function setTransaction(string $vendorOrderId, PaymentSystemEnum $paymentSystem, TransactionStatusEnum $status):void
    {
        $order = Order::where('vendor_order_id', $vendorOrderId)->firstOrFail();

        $order->transaction()->updateOrCreate([
            'payment_system' => $paymentSystem,
            'status' => $status,

        ]);

        $order->update([
            'status' => match ($status) {
                TransactionStatusEnum::Success => OrderStatusEnum::Paid,
                TransactionStatusEnum::Cancelled => OrderStatusEnum::Cancelled,
                default => OrderStatusEnum::InProcess,
            },
        ]);

    }

    protected function addProductsToOrder(Order $order): void
    {
        $items = Cart::all();
        $products = Product::whereIn('id', $items->pluck('id'))->get();

        $items->each(function ($item) use ($order, $products) {
            $product = $products->find($item['id']);

            $updatedQty = $product->quantity - $item['quantity'];

            if ($updatedQty < 0 || !$product->update(['quantity' => $updatedQty])){
                throw new \Exception("Not enough quantity for product [$product->title]");
            }

            $order->products()->attach(
                $product,
                [
                    'title' => $item['title'],
                    'quantity' => $item['quantity'],
                    'single_price' => $item['price'],

                ]
            );
        });
    }
}
