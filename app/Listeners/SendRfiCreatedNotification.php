<?php

namespace App\Listeners;

use App\Events\RfiCreated;
use App\Notifications\RfiCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRfiCreatedNotification
{
    public function handle(RfiCreated $event)
    {
        $event->rfi->projectDiscipline->lead->notify(new RfiCreatedNotification($event->rfi));
    }
}
