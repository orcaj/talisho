<?php

namespace App\Listeners;

use App\Events\DocumentationCreated;
use App\Notifications\DocumentationCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendDocumentationCreatedNotification
{
    public function handle(DocumentationCreated $event)
    {
         $event->documentation->assigned->notify(new DocumentationCreatedNotification($event->documentation));
    }
}
