<?php

namespace App\Notifications;

use App\RfiResponse;
use Illuminate\Notifications\Notification;

class RfiRespondedNotification extends Notification
{
    public $rfiResponse, $project;

    public function __construct(RfiResponse $rfiResponse)
    {
        $this->rfiResponse = $rfiResponse;
        $this->project = $rfiResponse->rfi->projectDiscipline->project;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->rfiResponse->rfi->projectDiscipline->lead->name . ' has responded to your Request for Information (RFI).'
        ];
    }
}
