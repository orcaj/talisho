<?php

namespace Tests\Feature;

use App\Enum\RegistrationStatus;
use App\Organization;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;
use Tests\Traits\Registerable;

class EmployeeListTest extends TestCase
{
    use RefreshDatabase, Registerable;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \TestingSeeder::class]);
    }

    /** @test */
    public function a_project_manager_should_only_see_employees_of_their_organization()
    {
        $user = UserWithRoleGenerator::createUser();

        $pmOrg = factory(Organization::class)->create();
        $projectManager = UserWithRoleGenerator::forOrganization($pmOrg)->createProjectManager();

        $this->signIn($projectManager);

        $attributes = ['id', 'email', 'first_name', 'last_name', 'status'];

        $this->get($pmOrg->path . '/users')
            ->assertPropEquals('users', function ($users) use ($projectManager, $attributes) {
                $this->assertEquals(
                    Arr::only($projectManager->toArray(), $attributes),
                    Arr::only($users[0], $attributes)
                );
            });
    }

    /** @test */
    public function a_users_registration_status_should_be_updated_after_registration()
    {
        $user = UserWithRoleGenerator::unregistered()->createUser();
        $projectManager = UserWithRoleGenerator::forOrganization($user->organization)->createProjectManager();

        $this->signIn($projectManager);

        $this->createInvite($user);

        $this->assertEquals(RegistrationStatus::INVITE_SENT, $user->status);

        $this->triggerSetProfile($user, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile_phone' => '555-555-5555'
        ]);

        $this->assertDatabaseHas('users', ['first_name' => 'John', 'last_name' => 'Doe', 'mobile_phone' => '555-555-5555']);

        $this->assertEquals(RegistrationStatus::REGISTERED, User::find($user->id)->status);
    }

    /** @test */
    public function deleting_a_user_retains_database_information()
    {

        $organization = factory(Organization::class)->create();
        $user = UserWithRoleGenerator::forOrganization($organization)->createUser();
        $projectManager = UserWithRoleGenerator::forOrganization($organization)->createProjectManager();

        $this->signIn($projectManager);

        $this->assertDatabaseHas('users', $user->getAttributes());
        $this->assertNull($user->deleted_at);

        $this->delete($user->path);

        $this->assertSoftDeleted($user);
    }
}
