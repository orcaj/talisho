<?php


namespace Tests\Feature;

use App\Organization;
use App\Project;
use App\ProjectDiscipline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Facades\Tests\SetupHelpers\RFITestFactory;

class ProjectSummaryTest extends TestCase
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
    public function late_rfis_should_not_count_towards_total_number_of_rfis_under_review()
    {
        $lead = UserWithRoleGenerator::createUser();
        $guest = UserWithRoleGenerator::createUser();

        $discipline = factory(ProjectDiscipline::class)->create([
            'project_id' => $this->project->id,
            'user_id' => $lead->id,
        ]);

        $underReview = RFITestFactory::forDiscipline($discipline)->createUnderReview();
        $underReview2 = RFITestFactory::forDiscipline($discipline)->createUnderReview();

        $late = RFITestFactory::forDiscipline($discipline)->createLate();

        $this->assertEquals(1, $discipline->countOfLateRFIs());
        $this->assertEquals(2, $discipline->countOfUnderReviewRFIs());
    }
}
