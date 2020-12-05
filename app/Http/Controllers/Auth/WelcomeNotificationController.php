<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserProfileFinalized;
use App\Http\Requests\Users\SetProfileRequest;
use App\Notifications\UserRegistered;
use App\Services\UserService;
use Illuminate\Foundation\Auth\RedirectsUsers;
use App\User;
use App\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

// TODO consider other names for this controller
class WelcomeNotificationController
{
    use RedirectsUsers;

    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function showWelcomeForm(Request $request, User $user)
    {
        return Inertia::render('User/SetProfile', [
            'user' => $user,
            'company' => $user -> company
        ]);
    }

    public function setProfile(SetProfileRequest $request, User $user)
    {
        $user->welcome_valid_until = null;

        $this->service->update($request, $user);

        UserProfileFinalized::dispatch($user);

        auth()->login($user);

        return redirect()->to($this->redirectPath());
    }
}
