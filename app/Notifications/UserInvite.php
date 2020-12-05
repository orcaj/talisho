<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;
use Spatie\WelcomeNotification\WelcomeNotification;

class UserInvite extends WelcomeNotification
{
    protected $sentBy;

    public function __construct(Carbon $validUntil, $sentBy = null)
    {
        parent::__construct($validUntil);

        $this->sentBy = $sentBy;
    }

    public function buildWelcomeNotificationMessage(): MailMessage
    {
        $openingLine = !is_null($this->sentBy)
            ? $this->sentBy->name . ' has invited you to participate in a project through the Taliho project management platform. Our records indicate that you are not yet registered with Taliho. Please click the button below to complete the  registration process and log in to the project.'
            : 'You have been invited to participate in a project through the Taliho project management platform. Our records indicate that you are not yet registered with Taliho. Please click the button below to complete the  registration process and log in to the project.';

        return (new MailMessage)
                ->subject($this->user-> organization ->name . ' Invitation')
                ->greeting('Hello, ' . $this->user->first_name . '!')
                ->line($openingLine)
                ->action(Lang::get('Taliho No-Fee Registration'), $this->showWelcomeFormUrl)
                ->line(new HtmlString('Should you have any questions about Taliho, please feel free to visit our website at ' . $this->addLinkForMainPage() . ', or contact our ' . $this->addLinkForSupportEmail() . ' at any time.'))
                ->line(Lang::get('Please note that this invitation will expire in :count hours.', ['count' => $this->validUntil->diffInHours() + 1]))
                ->salutation(new HtmlString('Welcome aboard, <br> <br>Taliho'));
    }

    private function addLinkForMainPage()
    {
        return sprintf('<a href="%s">%s</a>', config('app.marketing_url'), config('app.marketing_url'));
    }

    private function addLinkForSupportEmail()
    {
        return sprintf('<a href="mailto: %s">%s</a>', config('mail.system-notifications'), 'customer support team');
    }
}
