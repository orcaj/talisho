<?php

namespace Tests\Feature;

use App\Notifications\UserInvite;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;

class SendUserInviteTest extends TestCase
{
    use RefreshDatabase;

    private $authenticated;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \TestingSeeder::class]);

        $this->authenticated = $this->signIn(UserWithRoleGenerator::createProjectManager());
    }

    /** @test */
    public function a_user_can_be_invited_to_the_same_organization_as_the_inviter()
    {
        Notification::fake();

        $email = 'example@example.com';

        $this->post('/users', [
            'email' => $email,
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $user = User::whereEmail($email)->first();

        Notification::assertSentTo($user, UserInvite::class);

        $this->assertEquals($user->organization, $this->authenticated->organization);
    }

    /** @test */
    public function a_project_manager_cannot_invite_a_user_with_same_email_as_registered_user()
    {
        Notification::fake();

        $existingUser = factory(User::class)->state('registered')->create([
            'organization_id' => $this->authenticated->organization->id
        ]);

        $this->post(route('users.store'), [
            'email' => $existingUser->email,
            'first_name' => $existingUser->first_name,
            'last_name' => $existingUser->last_name
        ])
            ->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }

    /** @test */
    public function a_project_manager_must_provide_an_email_a_first_name_and_a_last_name_to_invite_users()
    {
        $this->post(route('users.store'))
            ->assertSessionHasErrors([
                'email',
                'first_name',
                'last_name'
            ]);
    }

    /** @test */
    public function a_project_manager_can_resend_an_invite_to_a_created_but_unregistered_user_in_their_organization()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'organization_id' => $this->authenticated->organization->id,
            'welcome_valid_until' => now()->addDays(30),
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $this->post(route('users.store'), [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name
        ]);

        Notification::assertSentTo($user, UserInvite::class);
    }

    /** @test */
    public function a_project_manager_cannot_resend_an_invite_to_an_unregistered_user_in_a_different_organization()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'welcome_valid_until' => now()->addDays(30)
        ]);

        $this->post(route('users.store'), [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name
        ])
            ->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }
}
