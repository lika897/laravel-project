<?php

namespace App\Mail\Enduser;

use App\Enums\QueuesEnum;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

//    public $queue = QueuesEnum::UsersNotifications->value;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order)
    {
        $this->onQueue(QueuesEnum::UsersNotifications);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->order->email,
            subject: 'Order Created Mail',
            tags: ['order', 'created']
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.created',
            with: [
                'url' => url(route('home')),
                'invoiceUrl' =>
                    url(
                        route(
                            'order.invoice', $this->order->vendor_order_id
                        )
                    ),
                'fullName' => ucfirst($this->order->name) . ' ' . ucfirst($this->order->surname),
                'items' => $this->order->products,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
