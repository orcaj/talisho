<?php


namespace Tests\Traits;


use App\Notifications\UserInvite;
use Illuminate\Foundation\Testing\TestResponse;

trait Registerable
{
    /** @var UserInvite Invite Notification */
    private $invite = null;

    public function createInvite($user)
    {
        $this->invite = (new UserInvite(now()->addDays(1), auth()->user()));
        $this->invite->toMail($user);
    }

    public function triggerSetProfile($user, $attributes = []): TestResponse
    {
        if ( is_null($this->invite)) {
            $this->createInvite($user);
        }

        return $this->post($this->invite->showWelcomeFormUrl, array_merge([
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $attributes));
    }
}
