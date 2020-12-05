<?php

namespace Tests\Feature;

use App\Documentation;
use App\Organization;
use App\OtherDocument;
use App\Project;
use App\ProjectDiscipline;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;

class MultipleDocumentationEditTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $organization;
    protected $project;
    protected $lead;
    protected $projectDiscipline;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \TestingSeeder::class]);

        $this->organization = factory(Organization::class)->create();

        $this->project = factory(Project::class)->create([
            'organization_id' => $this->organization->id
        ]);

        $this->lead = UserWithRoleGenerator::forOrganization($this->organization)->createUser();

        $this->projectDiscipline = factory(ProjectDiscipline::class)->create([
            'user_id' => $this->lead->id,
            'project_id' => $this->project->id,
        ]);

        $this->signIn($this->lead);
    }

    /** @test */
    public function a_lead_can_reassign_multiple_documents_to_a_single_user_at_once()
    {
        $otherDoc1 = factory(OtherDocument::class)->create([
            'project_discipline_id' => $this->projectDiscipline->id,
        ]);

        $otherDoc2 = factory(OtherDocument::class)->create([
            'project_discipline_id' => $this->projectDiscipline->id,
        ]);

        $doc1 = factory(Documentation::class)->create([
            'entity_id' => $otherDoc1->id,
            'entity_type' => OtherDocument::class,
            'lead_user_id' => $this->lead->id,
            'assigned_user_id' => $this->lead->id
        ]);

        $doc2 = factory(Documentation::class)->create([
            'entity_id' => $otherDoc2->id,
            'entity_type' => OtherDocument::class,
            'lead_user_id' => $this->lead->id,
            'assigned_user_id' => $this->lead->id
        ]);

        $this->assertEquals($this->lead->id, $doc1->assigned->id);
        $this->assertEquals($this->lead->id, $doc2->assigned->id);

        $guest = UserWithRoleGenerator::forOrganization($this->organization)->createUser();

        $response = $this->put(route('documentation.re-assign.multiple'), [
            'documentation_ids' => [
                $doc1->id,
                $doc2->id,
            ],
            'assigned_user_id' => $guest->id
        ]);

        $this->assertEquals($guest->id, $doc1->refresh()->assigned->id);
        $this->assertEquals($guest->id, $doc2->refresh()->assigned->id);
    }

    /** @test */
    public function a_lead_can_change_multiple_documentation_due_dates_at_once()
    {
        $otherDoc1 = factory(OtherDocument::class)->create([
            'project_discipline_id' => $this->projectDiscipline->id,
        ]);

        $otherDoc2 = factory(OtherDocument::class)->create([
            'project_discipline_id' => $this->projectDiscipline->id,
        ]);

        $doc1 = factory(Documentation::class)->create([
            'entity_id' => $otherDoc1->id,
            'entity_type' => OtherDocument::class,
            'lead_user_id' => $this->lead->id,
            'assigned_user_id' => $this->lead->id
        ]);

        $doc2 = factory(Documentation::class)->create([
            'entity_id' => $otherDoc2->id,
            'entity_type' => OtherDocument::class,
            'lead_user_id' => $this->lead->id,
            'assigned_user_id' => $this->lead->id
        ]);

        $this->assertEquals(null, $doc1->due_date);
        $this->assertEquals(null, $doc2->due_date);

        $fifteenDaysFromNow = now()->addDays(15)->startOfDay();

        $response = $this->put(route('documentation.due-date.multiple'), [
            'documentation_ids' => [
                $doc1->id,
                $doc2->id,
            ],
            'due_date' => $fifteenDaysFromNow
        ]);

        $this->assertEquals($fifteenDaysFromNow, $doc1->refresh()->due_date);
        $this->assertEquals($fifteenDaysFromNow, $doc2->refresh()->due_date);
    }
}
