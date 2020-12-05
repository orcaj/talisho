<?php

namespace App\Notifications;

use App\DocumentationSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentationSubmittedNotification extends Notification
{
    public $documentationSubmission, $project;

    public function __construct(DocumentationSubmission $documentationSubmission)
    {
        $this->documentationSubmission = $documentationSubmission;
        $this->project = $documentationSubmission->documentation->entity->projectDiscipline->project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'A document has been submitted for '
                . $this->documentationSubmission->documentation->entity->specification->code
                . ' - '
                . $this->documentationSubmission->documentation->entity->specification->name
        ];
    }
}
