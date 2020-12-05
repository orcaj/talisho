<?php

namespace App\Notifications;

use App\ConstructionDirective;
use Illuminate\Notifications\Notification;

class ConstructionDirectiveCreatedNotification extends Notification
{
    public $constructionDirective, $project;

    public function __construct(ConstructionDirective $constructionDirective)
    {
        $this->constructionDirective = $constructionDirective;
        $this->project = $constructionDirective->projectDiscipline->project;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have received a Construction Directive from ' . $this->constructionDirective->projectDiscipline->lead->name
        ];
    }
}
