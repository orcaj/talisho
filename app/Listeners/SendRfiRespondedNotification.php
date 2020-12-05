<?php

namespace App\Listeners;

use App\Events\RfiResponseCreated;
use App\Notifications\RfiRespondedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRfiRespondedNotification
{
    public function handle(RfiResponseCreated $event)
    {
        $event->rfiResponse->rfi->assignedTo->notify(new RfiRespondedNotification($event->rfiResponse));
    }
}
