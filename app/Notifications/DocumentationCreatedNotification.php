<?php

namespace App\Notifications;

use App\Documentation;
use Illuminate\Notifications\Notification;

class DocumentationCreatedNotification extends Notification
{
    public $documentation, $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Documentation $documentation)
    {
        $this->documentation = $documentation;
        $this->project = $documentation->entity->projectDiscipline->project;
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


    public function toArray($notifiable)
    {
        return [
            'message' => 'You have a new request for '
                . $this->documentation->entity->specification->code
                . ' - '
                . $this->documentation->entity->specification->name
        ];
    }
}
