<?php

namespace App\Listeners;

use App\Events\DocumentationReassigned;
use App\Notifications\DocumentationReassignedNotification;

class SendDocumentationReassignedNotification
{
    public function handle(DocumentationReassigned $event)
    {
        $event->documentation->assigned->notify(new DocumentationReassignedNotification($event->documentation));
    }
}
