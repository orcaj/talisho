<?php

namespace App\Listeners;

use App\Events\DocumentationResponded;
use App\Notifications\DocumentationRespondedNotification;

class SendDocumentationRespondedNotification
{
    public function handle(DocumentationResponded $event)
    {
        $event->documentationResponse->submission->documentation->assigned->notify(
            new DocumentationRespondedNotification($event->documentationResponse)
        );
    }
}
