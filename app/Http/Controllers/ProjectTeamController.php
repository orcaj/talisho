<?php

namespace App\Http\Controllers;

use App\Enum\Permission;
use App\Organization;
use App\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectTeamController extends Controller
{
    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $allDisciplines = $project->disciplines->load('discipline', 'lead.organization', 'team.organization', 'lead.company', 'team.company');

        return Inertia::render('Organization/Project/Team/Index', [
            'organization' => function() use ($organization) {
                return $organization;
            },
            'project' => function () use ($project) {
                return $project;
            },
            'disciplines' => function () use ($request, $allDisciplines) {
                return $request->user()->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES)
                    ? $allDisciplines
                    : $allDisciplines->filter->isVisibleFor($request->user())->flatten();
            },
            'projectManager' => function () use ($organization) {
                return $organization->projectManager;
            }
        ]);
    }
}
