<?php

namespace Tests\Feature;

use App\Events\UserProfileFinalized;
use App\Notifications\UserRegistered;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;
use Tests\Traits\Registerable;

class SetInitialProfileTest extends TestCase
{
    use RefreshDatabase, Registerable;

    private $user;
    private $projectManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \TestingSeeder::class]);

        $this->user = factory(User::class)->create();
        $this->projectManager = UserWithRoleGenerator::forOrganization($this->user->organization)->createProjectManager();
    }

    /** @test */
    public function it_sets_the_initial_profile_and_authenticates_the_user()
    {
        $newAttributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile_phone' => '555-555-5555'
        ];

        $this->assertDatabaseMissing('users', $newAttributes);

        $this->signIn($this->projectManager);

        $this->triggerSetProfile($this->user, $newAttributes)
            ->assertRedirect('/home')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', $newAttributes);

        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function a_created_user_cannot_login_without_setting_a_password_first()
    {
        $user = factory(User::class)->create();

        $this->assertFalse(auth()->validate([
            'email' => $user->email,
            'password' => $user->password
        ]));
    }

    /** @test */
    public function it_confirms_a_profile_requires_first_and_last_names()
    {
        $this->signIn($this->projectManager);

        $this->triggerSetProfile($this->user)
            ->assertSessionHasErrors(['first_name', 'last_name', 'mobile_phone']);
    }

    /** @test */
    public function an_event_is_dispatched_to_trigger_notifications_when_a_user_finalizes_their_profile()
    {
        Event::fake();

        $newAttributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile_phone' => '555-555-5555'
        ];

        $this->signIn($this->projectManager);

        $this->triggerSetProfile($this->user, $newAttributes)
            ->assertSessionHasNoErrors();

        Event::assertDispatched(UserProfileFinalized::class);
    }

    /** @test */
    public function it_confirms_a_project_manager_is_notified_when_a_user_registers_in_their_organization()
    {
        Notification::fake();

        $newAttributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile_phone' => '555-555-5555'
        ];

        $this->signIn($this->projectManager);

        $this->triggerSetProfile($this->user, $newAttributes)
            ->assertSessionHasNoErrors();

        Notification::assertSentTo($this->projectManager, UserRegistered::class);
    }

    /** @test */
    public function the_system_notification_email_receives_a_notification_when_any_users_finalize_their_profile()
    {
        Notification::fake();

        $newAttributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile_phone' => '555-555-5555'
        ];

        $this->signIn($this->projectManager);

        $this->triggerSetProfile($this->user, $newAttributes)
            ->assertSessionHasNoErrors();

        Notification::assertSentTo(
            new AnonymousNotifiable,
            UserRegistered::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === config('mail.system-notifications');
            }
        );
    }

}
