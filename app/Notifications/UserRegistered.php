<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification
{
    public $registeredUser;

    use Queueable;

    public function __construct(User $user)
    {
        $this->registeredUser = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->registeredUser-> last_team()){
            return (new MailMessage)
                ->line($this->registeredUser->name . ' has successfully been added to your '. $this->registeredUser-> last_team() -> project -> name .' team.');
        }
        else{
            return (new MailMessage)
                ->line($this->registeredUser->name . ' has successfully been added to your team.');
        }

    }
}
