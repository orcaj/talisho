<?php

namespace Tests\Feature;

use App\Discipline;
use App\Organization;
use App\Project;
use App\ProjectDiscipline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;

class ProjectTeamInviteTest extends TestCase
{
    use RefreshDatabase;
    protected $organization;
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \TestingSeeder::class]);

        $this->organization = factory(Organization::class)->create();

        $this->project = factory(Project::class)->create([
            'organization_id' => $this->organization->id
        ]);
    }

    /** @test */
    public function a_discipline_lead_can_add_guests_to_their_project_discipline()
    {
        // given

        $discLead = UserWithRoleGenerator::createUser();

        $projectDiscipline = factory(ProjectDiscipline::class)->create([
            'project_id' => $this->project->id,
            'discipline_id' => Discipline::first()->id,
            'user_id' => $discLead->id
        ]);

        $guest = UserWithRoleGenerator::createUser();


        $this->signIn($discLead);

        // when
        $this->post(route('project-discipline.users.create', [$projectDiscipline, $guest]))
            ->assertStatus(302);

        // then
        $this->assertTrue($projectDiscipline->team->contains($guest));
    }

    /** @test */
    public function a_discipline_lead_cannot_add_guests_to_other_disciplines_in_the_project()
    {
        $discLeadFirst = UserWithRoleGenerator::createUser();
        $discLeadSecond = UserWithRoleGenerator::createUser();

        $projectDisciplineFirst = factory(ProjectDiscipline::class)->create([
            'project_id' => $this->project->id,
            'discipline_id' => Discipline::first()->id,
            'user_id' => $discLeadFirst->id
        ]);

        $projectDisciplineSecond = factory(ProjectDiscipline::class)->create([
            'project_id' => $this->project->id,
            'discipline_id' => Discipline::all()->random()->id,
            'user_id' => $discLeadSecond->id
        ]);

        $guest = UserWithRoleGenerator::createUser();



        $this->signIn($discLeadFirst);
        $response = $this->post(route('project-discipline.users.create', [$projectDisciplineSecond, $guest]));


        $response->assertStatus(403);
    }

    /** @test */
    public function a_non_discipline_lead_cannot_add_guests_to_project_disciplines()
    {
        $user1 = UserWithRoleGenerator::createUser();
        $discLead = UserWithRoleGenerator::createUser();


        $guest = UserWithRoleGenerator::createUser();


        $projectDiscipline = factory(ProjectDiscipline::class)->create([
            'project_id' => $this->project->id,
            'discipline_id' => Discipline::first()->id,
            'user_id' => $discLead->id
        ]);

        $projectDiscipline->team()->attach($user1);


        $this->signIn($user1);
        $response = $this->post(route('project-discipline.users.create', [$projectDiscipline, $guest]));

        $response->assertStatus(403);
        $this->assertFalse($projectDiscipline->team->contains($guest));
    }

    /** @test */
    public function a_guest_can_belong_to_multiple_disciplines_within_a_project()
    {
        $discLead = UserWithRoleGenerator::createUser();

        $projectDisciplineFirst = factory(ProjectDiscipline::class)->create([
            'project_id' => $this->project->id,
            'discipline_id' => Discipline::first()->id,
            'user_id' => $discLead->id
        ]);

        $projectDisciplineSecond = factory(ProjectDiscipline::class)->create([
            'project_id' => $this->project->id,
            'discipline_id' => Discipline::all()->random()->id,
            'user_id' => $discLead->id
        ]);

        $guest = UserWithRoleGenerator::createUser();

        $projectDisciplineFirst->team()->attach($guest);


        $this->signIn($discLead);

        $response = $this->post(route('project-discipline.users.create', [$projectDisciplineSecond, $guest]));

        $response->assertStatus(302);

        $this->assertTrue($projectDisciplineSecond->team->contains($guest));
    }

}
