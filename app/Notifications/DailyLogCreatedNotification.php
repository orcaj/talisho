<?php

namespace App\Notifications;

use App\DailyLog;
use Illuminate\Notifications\Notification;

class DailyLogCreatedNotification extends Notification
{

    public $dailyLog, $project;

    public function __construct(DailyLog $dailyLog)
    {
        $this->dailyLog = $dailyLog;
        $this->project = $dailyLog->projectDiscipline->project;
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
        return [
            'message' => 'A daily log has been created for ' . $this->dailyLog->log_date_with_day_of_week
        ];
    }
}
