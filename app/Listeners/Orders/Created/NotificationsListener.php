<?php

namespace App\Listeners\Orders\Created;

use App\Enums\QueuesEnum;
use App\Events\Orders\CreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
    }
}
