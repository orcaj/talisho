<?php

namespace Tests\Feature\Authorization;

use App\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;

/**
 * Class OrganizationAuthorizationTest
 * @see \App\Policies\OrganizationPolicy
 */

class OrganizationAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \RolePermissionSeeder::class]);
    }

    /** @test */
    public function project_managers_can_edit_their_own_organizations()
    {
        $this->signIn(
            $projectManager = UserWithRoleGenerator::createProjectManager()
        );

        $this->get(route('organizations.edit', [$projectManager->organization]))
            ->assertStatus(200);
    }

    /** @test */
    public function project_managers_cannot_edit_other_organizations()
    {
        $org = factory(Organization::class)->create();

        $this->signIn(
            $projectManager = UserWithRoleGenerator::createProjectManager()
        );

        $this->get(route('organizations.edit', [$org]))
            ->assertStatus(403);
    }
}
