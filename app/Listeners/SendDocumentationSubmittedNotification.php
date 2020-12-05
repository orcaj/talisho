<?php

namespace App\Listeners;

use App\Events\DocumentationSubmitted;
use App\Notifications\DocumentationSubmittedNotification;

class SendDocumentationSubmittedNotification
{
    public function handle(DocumentationSubmitted $event)
    {
        $event->submission->documentation->lead->notify(
            new DocumentationSubmittedNotification($event->submission)
        );
    }
}
