<?php

namespace App\Traits;

use App\TalihoNotification;
use Illuminate\Notifications\Notifiable as BaseNotifiable;

trait TalihoNotifiable
{
    use BaseNotifiable;

    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(TalihoNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }
}
