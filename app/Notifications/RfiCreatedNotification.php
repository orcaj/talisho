<?php

namespace App\Notifications;

use App\Rfi;
use Illuminate\Notifications\Notification;

class RfiCreatedNotification extends Notification
{
    public $project, $rfi;

    public function __construct(Rfi $rfi)
    {
        $this->rfi = $rfi;
        $this->project = $rfi->projectDiscipline->project;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have a new Request for Information (RFI) from ' . $this->rfi->requestedBy->name
        ];
    }
}
