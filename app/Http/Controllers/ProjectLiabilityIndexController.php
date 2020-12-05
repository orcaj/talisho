<?php

namespace App\Http\Controllers;

use App\Enum\Permission;
use App\Organization;
use App\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectLiabilityIndexController extends Controller
{
    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $user = $request->user();
        $disciplines = $user->can(Permission::VIEW_ALL_LIABILITY)
            ? $project->disciplines
            : $project->disciplines->filter->isVisibleFor($user);

        return Inertia::render('Organization/Project/Liability/Index', [
            'organization' => $organization,
            'project' => $project,
            'projectDisciplines' => $disciplines->load('team', 'discipline', 'lead.organization')->values(),
            'tableData' => $disciplines->mapToGroups(function ($disc) use ($user) {
                return [
                    $disc->id => [
                        [
                            'title' => "Daily Logs",
                            'quantity' => $disc->dailyLogs->count(),
                            'status' => $disc->dailyLogStatus
                        ],
                        [
                            'title' => "Incident Reports",
                            'quantity' => $disc->incidentReports->filter->isVisibleForUser($user)->count(),
                            'status' => $disc->incidentReportStatus
                        ],
                    ],
                ];
            })
        ]);
    }
}
