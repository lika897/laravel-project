<?php

namespace App\Notifications\Orders\Created;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Middleware\WithoutOverlapping;

abstract class Base extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 60;
    public $maxExceptions = 3;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        //
    }
    public function middleware($notifiable, $channel)
    {
        $key = "{$channel}:{$notifiable->id}-{$this->order->vendor_order_id}";

        return match ($channel) {
            'mail' => [new WithoutOverlapping($key)],
            default => [],
        };
    }

}
