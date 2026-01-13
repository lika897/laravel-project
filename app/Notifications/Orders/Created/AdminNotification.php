<?php

namespace App\Notifications\Orders\Created;

use App\Enums\QueuesEnum;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;

class AdminNotification extends Base
{

    public function viaQueues(): array
    {
        return [
            'mail' => QueuesEnum::AdminNotifications->value,
        ];

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $user): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $user): MailMessage
    {
        return (new MailMessage)
            ->subject('New Order #' . $this->order->id)
            ->line("Hello $user->name,")
            ->line('There is a new order placed.')
            ->action('Order invoice', url(route(
                'order.invoice', $this->order->vendor_order_id
            )))
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
//    public function toArray(object $notifiable): array
//    {
//        return [
//            //
//        ];
//    }
}
