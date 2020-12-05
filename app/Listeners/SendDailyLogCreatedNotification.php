<?php

namespace App\Listeners;

use App\Events\DailyLogCreated;
use App\Notifications\DailyLogCreatedNotification;

class SendDailyLogCreatedNotification
{
    public function handle(DailyLogCreated $event)
    {
        $event->dailyLog->projectDiscipline->lead->notify(new DailyLogCreatedNotification($event->dailyLog));
    }
}
