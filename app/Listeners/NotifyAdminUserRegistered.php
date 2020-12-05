<?php

namespace App\Listeners;

use App\Events\UserProfileFinalized;
use App\Notifications\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminUserRegistered implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(UserProfileFinalized $event)
    {
        Notification::route('mail', config('mail.system-notifications'))->notify(new UserRegistered($event->user));
    }
}
