<?php

namespace App\Listeners;

use App\Events\UserProfileFinalized;
use App\Notifications\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyProjectManagerUserFinalizedRegistration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserProfileFinalized $event)
    {
        $event->user->organization->projectManager->notify(new UserRegistered($event->user));
    }
}
