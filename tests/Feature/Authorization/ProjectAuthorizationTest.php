<?php

namespace Tests\Feature\Authorization;

use App\Organization;
use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;


/**
 * Class ProjectAuthorizationTest
 * @see \App\Policies\ProjectPolicy
 */

class ProjectAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \RolePermissionSeeder::class]);
    }

    // Create

    /** @test */
    public function a_project_manager_can_create_a_project_for_their_organization()
    {
        $this->signIn($projectManager = UserWithRoleGenerator::createProjectManager());

        $this->get(route('organizations.projects.create', [$projectManager->organization]))
            ->assertStatus(200);
    }

    /** @test */
    public function a_project_manager_cannot_create_a_project_for_another_organization()
    {
        $this->signIn($projectManager = UserWithRoleGenerator::createProjectManager());

        $otherOrganization = factory(Organization::class)->create();

        $this->get(route('organizations.projects.create', [$otherOrganization]))
            ->assertStatus(403);
    }

    /** @test */
    public function other_users_cannot_access_project_creation_page()
    {
        $this->signIn($user = UserWithRoleGenerator::createUser());

        $this->get(route('organizations.projects.create', [$user->organization]))
            ->assertStatus(403);
    }

    // Edit

    /** @test */
    public function a_project_manager_can_access_project_edit_page_for_their_organization()
    {
        $this->signIn($projectManager = UserWithRoleGenerator::createProjectManager());

        $project = factory(Project::class)->create(['organization_id' => $projectManager->organization->id]);

        $this->get(route('organizations.projects.edit', [$projectManager->organization, $project]))
            ->assertStatus(200);
    }

    /** @test */
    public function a_projec_manager_cannot_access_project_edit_page_for_another_organization()
    {
        $this->signIn($projectManager = UserWithRoleGenerator::createProjectManager());

        $otherOrg = factory(Organization::class)->create();

        $project = factory(Project::class)->create(['organization_id' => $otherOrg->id]);

        $this->get(route('organizations.projects.edit', [$otherOrg, $project]))
            ->assertStatus(403);
    }

    /** @test */
    public function other_users_cannot_access_project_edit_page()
    {
        $this->signIn($user = UserWithRoleGenerator::createUser());

        $project = factory(Project::class)->create(['organization_id' => $user->organization->id]);

        $this->get(route('organizations.projects.edit', [$user->organization, $project]))
            ->assertStatus(403);
    }

    // Show



    // Delete



}
