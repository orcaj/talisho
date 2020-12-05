<?php

namespace App\Notifications;

use App\Documentation;
use Illuminate\Notifications\Notification;

class DocumentationReassignedNotification extends Notification
{
    public $documentation, $project;

    public function __construct(Documentation $documentation)
    {
        $this->documentation = $documentation;
        $this->project = $documentation->entity->projectDiscipline->project;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A document for ' . $this->documentation->entity->specification->code . ' - ' . $this->documentation->entity->specification->name . ' has been assigned to you.'
        ];
    }
}
