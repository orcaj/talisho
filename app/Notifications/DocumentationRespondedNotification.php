<?php

namespace App\Notifications;

use App\DocumentationResponse;
use Illuminate\Notifications\Notification;

class DocumentationRespondedNotification extends Notification
{
    public $documentationResponse, $project;

    public function __construct(DocumentationResponse $documentationResponse)
    {
        $this->documentationResponse = $documentationResponse;
        $this->project = $documentationResponse->submission->documentation->entity->projectDiscipline->project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $status = $this->documentationResponse->approvesDocument ? 'approved' : 'rejected';
        return [
            'message' => 'Document for ' . $this->documentationResponse->submission->documentation->entity->specification->code
            . ' - ' . $this->documentationResponse->submission->documentation->entity->specification->name
            . ' has been ' . $status . '.'
        ];
    }
}
