<?php

namespace Tests\Feature;

use App\CSI;
use App\CustomSpecification;
use App\Enum\DocumentType;
use App\Enum\MessagingStatus;
use App\Organization;
use App\OtherDocument;
use App\Project;
use App\ProjectDiscipline;
use App\Submittal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Tests\TestCase;

class CreateDocumentationTest extends TestCase
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
    public function a_lead_can_assign_a_submittal_and_associated_documents_to_themselves_with_standard_CSIs_and_no_due_date()
    {
        $csi1 = factory(CSI::class)->create();
        $csi2 = factory(CSI::class)->create();

        $this->post(route('documentation.submittal.store', [$this->projectDiscipline, $this->lead]), [
            'submittal' => [
                'name' => $csi1->name,
                'code' => $csi1->code,
            ],
            'associated_document_ids' => [$csi2->id],
            'due_date' => null,
        ]);

        $this->assertDatabaseHas('submittals', [
            'project_discipline_id' => $this->projectDiscipline->id,
            'specification_type' => CSI::class,
            'specification_id' => $csi1->id
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => Submittal::first()->id,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::SUBMITTAL,
            'specification_type' => CSI::class,
            'specification_id' => $csi2->id,
        ]);

        $this->assertDatabaseHas('documentations', [
            'assigned_user_id' => $this->lead->id,
            'lead_user_id' => $this->lead->id,
            'entity_type' => Submittal::class,
            'entity_id' => Submittal::first()->id,
            'status' => MessagingStatus::OUTSTANDING,
            'due_date' => null,
        ]);

        $this->assertDatabaseHas('documentations', [
            'assigned_user_id' => $this->lead->id,
            'lead_user_id' => $this->lead->id,
            'entity_type' => OtherDocument::class,
            'entity_id' => OtherDocument::first()->id,
            'status' => MessagingStatus::OUTSTANDING,
            'due_date' => null,
        ]);
    }

    /** @test */
    public function a_lead_can_assign_a_submittal_using_a_new_custom_specification()
    {
        $associatedDocument = factory(CSI::class)->create();

        $this->post(route('documentation.submittal.store', [$this->projectDiscipline, $this->lead]), [
            'submittal' => [
                'name' => 'New Name Not Created Before',
                'code' => '012345',
            ],
            'associated_document_ids' => [$associatedDocument->id],
            'due_date' => null,
        ]);

        $this->assertDatabaseHas('submittals', [
            'project_discipline_id' => $this->projectDiscipline->id,
            'specification_type' => CustomSpecification::class,
            'specification_id' => CustomSpecification::first()->id
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => Submittal::first()->id,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::SUBMITTAL,
            'specification_type' => CSI::class,
            'specification_id' => $associatedDocument->id,
        ]);
    }

    /** @test */
    public function a_lead_can_assign_a_general_requirement_from_existing_CSIs()
    {
        $csi1 = factory(CSI::class)->create();
        $csi2 = factory(CSI::class)->create();

        $this->post(route('documentation.general.store', [$this->projectDiscipline, $this->lead]), [
            'specifications' => [
                [
                    'name' => $csi1->name,
                    'code' => $csi1->code
                ],
                [
                    'name' => $csi2->name,
                    'code' => $csi2->code
                ]
            ],
            'due_date' => null,
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::GENERAL_REQUIREMENT,
            'specification_type' => CSI::class,
            'specification_id' => $csi1->id,
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::GENERAL_REQUIREMENT,
            'specification_type' => CSI::class,
            'specification_id' => $csi2->id,
        ]);
    }

    /** @test */
    public function a_lead_can_assign_a_general_requirement_from_new_custom_specifications()
    {
        $firstCode = '012345';
        $secondCode = '543210';

        $this->post(route('documentation.general.store', [$this->projectDiscipline, $this->lead]), [
            'specifications' => [
                [
                    'name' => 'Random name 1',
                    'code' => $firstCode
                ],
                [
                    'name' => 'Another new name',
                    'code' => $secondCode
                ]
            ],
            'due_date' => null,
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::GENERAL_REQUIREMENT,
            'specification_type' => CustomSpecification::class,
            'specification_id' => CustomSpecification::firstWhere('code', $firstCode)->id,
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::GENERAL_REQUIREMENT,
            'specification_type' => CustomSpecification::class,
            'specification_id' => CustomSpecification::firstWhere('code', $secondCode)->id,
        ]);

    }

    /** @test */
    public function a_lead_can_assign_a_general_requirement_from_existing_custom_specifications()
    {
        $customSpec1 = factory(CustomSpecification::class)->create([
            'organization_id' => $this->organization->id,
            'type' => DocumentType::GENERAL_REQUIREMENT
        ]);

        $customSpec2 = factory(CustomSpecification::class)->create([
            'organization_id' => $this->organization->id,
            'type' => DocumentType::GENERAL_REQUIREMENT
        ]);

        $customSpec3 = factory(CustomSpecification::class)->create([
            'organization_id' => $this->organization->id,
            'type' => DocumentType::GENERAL_REQUIREMENT
        ]);

        $this->post(route('documentation.general.store', [$this->projectDiscipline, $this->lead]), [
            'specifications' => [
                [
                    'name' => $customSpec1->name,
                    'code' => $customSpec1->code
                ],
                [
                    'name' => $customSpec2->name,
                    'code' => $customSpec2->code
                ]
            ],
            'due_date' => null,
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::GENERAL_REQUIREMENT,
            'specification_type' => CustomSpecification::class,
            'specification_id' => $customSpec1->id,
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::GENERAL_REQUIREMENT,
            'specification_type' => CustomSpecification::class,
            'specification_id' => $customSpec2->id,
        ]);

        $this->assertDatabaseMissing('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::GENERAL_REQUIREMENT,
            'specification_type' => CustomSpecification::class,
            'specification_id' => $customSpec3->id,
        ]);

    }

    /** @test */
    public function a_lead_can_assign_a_tab_cx_leed_document_with_new_custom_specifications()
    {
        $firstCode = '012345';
        $secondCode = '543210';

        $this->post(route('documentation.tab.store', [$this->projectDiscipline, $this->lead]), [
            'specifications' => [
                [
                    'name' => 'Random name 1',
                    'code' => $firstCode
                ],
                [
                    'name' => 'Another new name',
                    'code' => $secondCode
                ]
            ],
            'due_date' => null,
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::TAB_CX_LEED,
            'specification_type' => CustomSpecification::class,
            'specification_id' => CustomSpecification::firstWhere('code', $firstCode)->id,
        ]);

        $this->assertDatabaseHas('other_documents', [
            'submittal_id' => null,
            'project_discipline_id' => $this->projectDiscipline->id,
            'document_type' => DocumentType::TAB_CX_LEED,
            'specification_type' => CustomSpecification::class,
            'specification_id' => CustomSpecification::firstWhere('code', $secondCode)->id,
        ]);

    }

}
