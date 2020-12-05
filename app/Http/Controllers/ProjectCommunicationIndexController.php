<?php

namespace App\Http\Controllers;

use App\Enum\Permission;
use App\Organization;
use App\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectCommunicationIndexController extends Controller
{
    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $user = $request->user();
        $disciplines = $user->can(Permission::VIEW_ALL_COMMUNICATION)
            ? $project->disciplines
            : $project->disciplines->filter->isVisibleFor($user);

        return Inertia::render('Organization/Project/Communication/Index', [
            'organization' => $organization,
            'project' => $project,
            'projectDisciplines' => $disciplines->load('team', 'discipline', 'lead.organization')->values(),
            'tableData' => $disciplines->mapToGroups(function ($disc) use ($user) {
                return [
                    $disc->id => [
                        [
                            'title' => "RFIs",
                            'quantity' => $disc->rfis->filter->isVisibleForUser($user)->count(),
                            'status' => $disc->rfiStatus($user)
                        ],
                        [
                            'title' => "Construction Directives",
                            'quantity' => $count = $disc->constructionDirectives->filter->isVisibleForUser($user)->count(),
                            'status' => $disc->constructionDirectiveStatus
                        ],
                    ],
                ];
            })
        ]);
    }
}
