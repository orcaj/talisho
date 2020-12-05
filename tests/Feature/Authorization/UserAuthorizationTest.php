<?php

namespace Tests\Feature\Authorization;

use App\Discipline;
use App\Organization;
use App\Project;
use App\ProjectDiscipline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;

/**
 * Class UserAuthorizationTest
 * @see \App\Policies\UserPolicy
 */

class UserAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \RolePermissionSeeder::class]);
        $this->artisan('db:seed', ['--class' => \DisciplineSeeder::class]);

    }

    // Index

    /** @test */
    public function project_managers_can_view_a_list_of_their_organizations_employees()
    {
        $projectManager = UserWithRoleGenerator::createProjectManager();

        $this->signIn($projectManager);

        $this->get(route('organizations.users.index', [$projectManager->organization]))
            ->assertStatus(200);
    }

    /** @test */
    public function project_managers_cannot_view_employees_from_other_organizations()
    {
        $otherOrg = factory(Organization::class)->create();
        $projectManager = UserWithRoleGenerator::createProjectManager();

        $this->signIn($projectManager);

        $this->get(route('organizations.users.index', [$otherOrg]))
            ->assertStatus(403);
    }

    /** @test */
    public function other_users_cannot_view_list_of_organization_employees()
    {
        $user = UserWithRoleGenerator::createUser();

        $this->signIn($user);

        $this->get(route('organizations.users.index', [$user->organization]))
            ->assertStatus(403);
    }

    // Create

    /** @test */
    public function project_managers_can_invite_users_to_their_organization()
    {
        $projectManager = UserWithRoleGenerator::createProjectManager();

        $this->signIn($projectManager);

        $this->get(route('users.create'))
            ->assertStatus(200);
    }

    /** @test */
    public function leads_can_invite_users_to_their_organization()
    {
        $discLead = UserWithRoleGenerator::createUser();

        $project = factory(Project::class)->create([
            'organization_id' => factory(Organization::class)->create()
        ]);

        $this->signIn($discLead);

        $this->get(route('users.create'))
            ->assertStatus(403);

        $projectDiscipline = factory(ProjectDiscipline::class)->create([
            'project_id' => $project->id,
            'discipline_id' => Discipline::first()->id,
            'user_id' => $discLead->id
        ]);

        $this->get(route('users.create'))
            ->assertStatus(200);
    }

    /** @test */
    public function other_users_cannot_invite_users()
    {
        $user = UserWithRoleGenerator::createUser();

        $this->signIn($user);

        $this->get(route('users.create'))
            ->assertStatus(403);
    }

    // Edit

    /** @test */
    public function a_user_can_access_their_own_profile_edit_page()
    {
        $user = UserWithRoleGenerator::createUser();

        $this->signIn($user);

        $this->get(route('users.edit', [$user]))
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_cannot_access_another_users_profile_edit_page()
    {
        $user = UserWithRoleGenerator::createUser();
        $otherUser = UserWithRoleGenerator::createUser();

        $this->signIn($user);

        $this->get(route('users.edit', [$otherUser]))
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_update_their_own_profile()
    {
        $user1 = UserWithRoleGenerator::createUser();
        $user2 = UserWithRoleGenerator::createUser();

        $originalFirst = $user2->first_name;
        $originalLast = $user2->last_name;

        $this->signIn($user1);

        $this->put($user2->path, [
            'first_name' => 'NewFirst',
            'last_name' => 'NewLast'
        ])->assertStatus(403);

        $this->assertEquals($user2->first_name, $originalFirst);
        $this->assertEquals($user2->last_name, $originalLast);
    }

    // Delete

    /** @test */
    public function a_project_manager_can_delete_users_within_their_own_organization()
    {
        $org = factory(Organization::class)->create();
        $user = UserWithRoleGenerator::forOrganization($org)->createUser();
        $projectManager = UserWithRoleGenerator::forOrganization($org)->createProjectManager();

        $this->signIn($projectManager);

        $this->delete($user->path)
            ->assertStatus(302);

        $this->assertSoftDeleted($user);
    }

    /** @test */
    public function project_managers_cannot_delete_users_in_other_organizations()
    {
        $user = UserWithRoleGenerator::createUser();
        $projectManager = UserWithRoleGenerator::createProjectManager();

        $this->signIn($projectManager);

        $this->delete($user->path)
            ->assertStatus(403);

        $this->assertNull($user->deleted_at);
    }

    /** @test */
    public function regular_users_cannot_delete_other_users()
    {
        $user = UserWithRoleGenerator::createUser();
        $otherUser = UserWithRoleGenerator::createUser();

        $this->signIn($user);

        $this->delete($otherUser->path)
            ->assertStatus(403);

        $this->assertNull($otherUser->deleted_at);
    }
}
