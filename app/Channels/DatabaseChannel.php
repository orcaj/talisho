<?php

namespace App\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseChannel extends BaseDatabaseChannel
{
    protected function buildPayload($notifiable, Notification $notification)
    {
        return [
            'id' => $notification->id,
            'type' => get_class($notification),
            'project_id' => $notification->project->id,
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
            'deleted_at' => null,
        ];
    }
}
