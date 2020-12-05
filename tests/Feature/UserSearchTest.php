<?php

namespace Tests\Feature;

use App\Organization;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;

class UserSearchTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $organization;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \TestingSeeder::class]);

        $this->user = UserWithRoleGenerator::forOrganization(
            $this->organization = factory(Organization::class)->create()
        )->createProjectManager();
    }

    /** @test */
    public function searching_only_returns_first_ten_users()
    {
        $this->organization->employees()->saveMany(
            factory(User::class, 15)->state('registered')->create()
        );

        $this->signIn($this->user);

        $this->get('/users/search')
            ->assertJsonCount(10);
    }

    /** @test */
    public function project_managers_can_search_for_users_in_their_organization_by_name()
    {
        $this->organization->employees()->saveMany(
            factory(User::class, 15)->state('registered')->create()
        )->last()->update(['first_name' => 'Very Specific', 'last_name' => 'Person Name']);

        $this->signIn($this->user);

        $this->getWithQueryString(
            '/organizations/' . $this->organization->id . '/users/search',
            ['search' => 'Very Specific']
        )->assertJsonCount(1);
    }

    /** @test */
    public function project_managers_can_search_for_users_outside_their_organization_by_email()
    {
        $this->organization->employees()->saveMany(
            factory(User::class, 15)->state('registered')->create()
        )->last()->update(['email' => 'superspecific@example.com']);

        $this->signIn($this->user);

        $this->getWithQueryString(
            '/users/search',
            ['search' => 'superspecific']
        )
            ->assertJsonCount(1);
    }
}
