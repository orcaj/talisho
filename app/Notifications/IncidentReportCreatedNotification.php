<?php

namespace App\Notifications;

use App\IncidentReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class IncidentReportCreatedNotification extends Notification
{
    public $incidentReport, $project;

    public function __construct(IncidentReport $incidentReport)
    {
        $this->incidentReport = $incidentReport;
        $this->project = $incidentReport->projectDiscipline->project;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'An incident report has been created for ' . $this->incidentReport->incident_date_with_day_of_week
        ];
    }
}
