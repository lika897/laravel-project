<?php

namespace App\Listeners\Orders\Created;

use App\Enums\QueuesEnum;
use App\Enums\RoleEnum;
use App\Events\Orders\CreatedEvent;
use App\Models\User;
use App\Notifications\Orders\Created\AdminNotification;
use App\Notifications\Orders\Created\UsersNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotificationsListener implements ShouldQueue
{
   public function viaQueue(): string
   {
       return QueuesEnum::Default->value;
   }

    /**
     * Handle the event.
     */
    public function handle(CreatedEvent $event): void
    {
        logs()->info('[NotificationsListener] Creating new notifications...]', ['event' => $event]);

        Notification::send(
            User::role(RoleEnum::ADMIN->value)->get(),
            app(
                AdminNotification::class,
                ['order' => $event->order]
            )
        );

        $event->order->notify(
            app(
                UsersNotification::class,
                ['order' => $event->order]
            )
        );
    }
}
