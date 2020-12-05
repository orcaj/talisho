<?php

namespace App\Listeners;

use App\Events\IncidentReportCreated;
use App\Notifications\IncidentReportCreatedNotification;

class SendIncidentReportCreatedNotification
{
    public function handle(IncidentReportCreated $event)
    {
        $event->incidentReport->projectDiscipline->lead->notify(new IncidentReportCreatedNotification($event->incidentReport));
    }
}
