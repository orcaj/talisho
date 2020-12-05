<?php

namespace App\Listeners;

use App\Events\ConstructionDirectiveCreated;
use App\Notifications\ConstructionDirectiveCreatedNotification;

class SendConstructionDirectiveCreatedNotification
{
    public function handle(ConstructionDirectiveCreated $event)
    {
        $event->constructionDirective->guests->each->notify(new ConstructionDirectiveCreatedNotification($event->constructionDirective));
    }
}
