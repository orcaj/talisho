<?php

namespace App\Http\Controllers;

use App\Enum\Permission;
use App\Organization;
use App\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowIncidentReportController extends Controller
{
    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $user = $request->user();
        $disciplines = $user->can(Permission::VIEW_ALL_LIABILITY)
            ? $project->disciplines
            : $project->disciplines->filter->isVisibleFor($user);

        return Inertia::render('Organization/Project/Liability/IncidentReport/Show', [
            'organization' => $organization,
            'project' => $project,
            'disciplines' => $disciplines->load('discipline', 'lead')->values(),
            'reports' => $disciplines->load('incidentReports.reportedBy.organization', 'incidentReports.files')->pluck('incidentReports')->flatten()->filter->isVisibleForUser($user)->values()
        ]);
    }
}
