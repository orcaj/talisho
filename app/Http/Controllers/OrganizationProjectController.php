<?php

namespace App\Http\Controllers;

use App\Discipline;
use App\Enum\Permission;
use App\Http\Requests\Projects\SaveProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Organization;
use App\Project;
use App\ProjectDiscipline;
use App\Services\ProjectDisciplineStatusesService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganizationProjectController extends Controller
{
    protected $projectService;
    protected $statusService;

    public function __construct(ProjectService $projectService, ProjectDisciplineStatusesService $statusesService)
    {
        $this->projectService = $projectService;
        $this->statusService = $statusesService;
    }

    public function index(Request $request, Organization $organization)
    {
        $this->authorize('viewAny', [Project::class, $organization]);

        $mprojects = $request->user()->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES)
            ? $organization->projects->load('disciplines.discipline', 'disciplines.lead'): [];

        $gprojects = $request->user()->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES)? [] : Project::whereUserBelongsToProjectTeam($request->user())->get();

        $notifications = $request->user()->notifications->groupBy('project_id');

        return Inertia::render('Organization/Project/Index', [
            'organization' => $organization,
            'company' =>  $request->user() -> company,
            'mcount' =>  $mprojects? $mprojects->count() : 0,
            'mprojects' => $mprojects? $mprojects->each(function($project) use ($notifications, $request) {
                $project->disciplines->each(function($discipline) use ($request) {
                    $discipline->setAttribute('status', $discipline->statuses($request->user()));
                    $discipline->loadMissing('discipline', 'project', 'lead');
                });

                $project->append('summaryData');

                $project->setAttribute(
                    'status',
                    $project->deriveStatus($project->disciplines->pluck('status'))
                );

                $project->setAttribute(
                    'notifications',
                    $notifications->get($project->id) ?: []
                );
            }): [],
            'gcount' => $gprojects? $gprojects->count() : 0,
            'gprojects' =>$gprojects? $gprojects->each(function($project) use ($notifications, $request) {
                $project->disciplines->each(function($discipline) use ($request) {
                    $discipline->setAttribute('status', $discipline->statuses($request->user()));
                    $discipline->loadMissing('discipline', 'project', 'lead');
                });

                $project->append('summaryData');

                $project->setAttribute(
                    'status',
                    $project->deriveStatus($project->disciplines->pluck('status'))
                );

                $project->setAttribute(
                    'notifications',
                    $notifications->get($project->id) ?: []
                );
            }) : []


        ]);
    }

    public function create(Organization $organization)
    {
        $this->authorize('create', [Project::class, $organization]);

        $projectManager = $organization->projectManager->only('id', 'first_name', 'last_name');

        return Inertia::render('Organization/Project/Create', [
            'disciplines' => function () {
                return Discipline::all();
            },
            'projectManager' => function () use ($organization, $projectManager) {
                return $projectManager;
            },
            'defaultDisciplines' => function () use ($organization, $projectManager) {
                return $organization->projectDefaultDisciplines->map(function ($discipline) use ($projectManager) {
                    $discipline['lead'] = $projectManager;
                    return $discipline;
                });
            },
            'routes' => function () use ($organization) {
                return [
                    'submit' => [
                        'route' => route('organizations.projects.store', ['organization' => $organization->id]),
                        'method' => 'post'
                    ]
                ];
            }
        ]);
    }

    public function store(SaveProjectRequest $request, Organization $organization)
    {
        $this->authorize('create', [Project::class, $organization]);

        $project = $this->projectService->createProject($request, $organization);
        return redirect(route('organizations.projects.documentation.index', [$organization, $project]))->with('success', 'Project creation successful');
    }


    public function show(Request $request, Organization $organization, Project $project)
    {
        //
    }

    public function edit(Organization $organization, Project $project)
    {
        $this->authorize('update', $project);

        return Inertia::render('Organization/Project/Edit', [
            'project' => function () use ($project) {
                return $project->load('disciplines.discipline', 'disciplines.lead');
            },
            'organization' => function () use ($organization) {
                return $organization;
            },
        ]);
    }

    public function update(UpdateProjectRequest $request, Organization $organization, Project $project)
    {
        $this->authorize('update', $project);

        $this->projectService->updateProject($request, $organization, $project);
        return redirect(route('organizations.projects.documentation.index', [$organization, $project]))->with('success', 'Project modification successful');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
