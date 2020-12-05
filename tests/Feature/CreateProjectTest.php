<?php

namespace Tests\Feature;

use App\Discipline;
use App\Notifications\ProjectCreated;
use App\Organization;
use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\SetupHelpers\UserWithRoleGenerator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $organization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \TestingSeeder::class]);

        $this->organization = factory(Organization::class)->create();

        Notification::fake();
    }

    /** @test */
    public function a_project_manager_can_persist_a_project()
    {
        $this->withoutExceptionHandling();
        $projectManager = UserWithRoleGenerator::forOrganization($this->organization)->createProjectManager();

        $this->signIn($projectManager);

        $projectData = [
            'generalInfo' => [
                'clientName' => $this->faker->company,
                'projectName' => $this->faker->name,
                'city' => $this->faker->city,
                'street' => $this->faker->streetAddress,
                'state' => $this->faker->state,
                'zip' => $this->faker->postcode,
                'description' => $this->faker->paragraph,
            ],
            'disciplineData' => [
                'saveDefaults' => true,
                'selected' => [
                    [
                        'id' => Discipline::all()->random()->id,
                        'lead' => [
                            'id' => $projectManager->id
                        ],
                    ],
                    [
                        'id' => Discipline::all()->random()->id,
                        'lead' => [
                            'id' => $projectManager->id
                        ]
                    ]
                ],
            ]
        ];

        $this->post(route('organizations.projects.store', [$this->organization]), $projectData);

        $createdProject = Project::first();

        $this->assertEquals($projectManager->organization->id, $createdProject->organization->id);

        // project created successfully
        $this->assertDatabaseHas('projects', [
            'client_name' => $projectData['generalInfo']['clientName'],
            'name' => $projectData['generalInfo']['projectName'],
            'city' => $projectData['generalInfo']['city'],
            'address_1' => $projectData['generalInfo']['street'],
            'state' => $projectData['generalInfo']['state'],
            'zip' => $projectData['generalInfo']['zip'],
            'description' => $projectData['generalInfo']['description']
        ]);

        // successfully associate disciplines to project with project manager as the lead for all disciplines
        $this->assertDatabaseHas('project_disciplines', [
            'project_id' => $createdProject->id,
            'discipline_id' => $createdProject->disciplines[0]->discipline->id,
            'user_id' => $projectManager->id
        ]);

        $this->assertDatabaseHas('project_disciplines', [
            'project_id' => $createdProject->id,
            'discipline_id' => $createdProject->disciplines[1]->discipline->id,
            'user_id' => $projectManager->id
        ]);

        // confirm that the selected disciplines are being saved as default disciplines
        $this->assertDatabaseHas('project_default_disciplines', [
            'organization_id' => $projectManager->organization->id,
            'discipline_id' => $createdProject->disciplines[0]->discipline->id
        ]);

        $this->assertDatabaseHas('project_default_disciplines', [
            'organization_id' => $projectManager->organization->id,
            'discipline_id' => $createdProject->disciplines[1]->discipline->id
        ]);
    }

    /** @test */
    public function the_system_notification_email_receives_a_notification_when_a_project_is_created()
    {
        $projectManager = UserWithRoleGenerator::forOrganization($this->organization)->createProjectManager();

        $this->signIn($projectManager);

        $projectData = [
            'generalInfo' => [
                'clientName' => $this->faker->company,
                'projectName' => $this->faker->name,
                'city' => $this->faker->city,
                'street' => $this->faker->streetAddress,
                'state' => $this->faker->state,
                'zip' => $this->faker->postcode,
                'description' => $this->faker->paragraph,
            ],
            'disciplineData' => [
                'saveDefaults' => true,
                'selected' => [
                    [
                        'id' => Discipline::all()->random()->id,
                        'lead' => [
                            'id' => $projectManager->id
                        ],
                    ],
                    [
                        'id' => Discipline::all()->random()->id,
                        'lead' => [
                            'id' => $projectManager->id
                        ]
                    ]
                ],
            ]
        ];

        $this->post(route('organizations.projects.store', [$this->organization]), $projectData);

        Notification::assertSentTo(
            new AnonymousNotifiable,
            ProjectCreated::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === config('mail.system-notifications');
            }
        );
    }
}
