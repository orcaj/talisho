<?php

namespace App\Http\Controllers;

use App\Enum\Permission;
use App\Organization;
use App\Project;
use App\Traits\MapsStatuses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowRfiController extends Controller
{
    use MapsStatuses;

    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $user = $request->user();
        $disciplines = $user->can(Permission::VIEW_ALL_COMMUNICATION)
            ? $project->disciplines
            : $project->disciplines->filter->isVisibleFor($user);

        return Inertia::render('Organization/Project/Communication/RFI/Show.vue', [
            'communications' => $disciplines->pluck('rfis')->flatten()->filter->isVisibleForUser($user)->map(function ($rfi) use ($user) {
                return [
                    'id' => $rfi->id,
                    'identifier' => $rfi->identifier,
                    'subject' => $rfi->subject,
                    'status' => $rfi->status,
                    'light_status' => $this->rfiStatusMap($rfi, $user),
                    'from' => $rfi->requestedBy->name,
                    'to' => $rfi->hasResponse() ? $rfi->response->respondedBy->name : $rfi->projectDiscipline->lead->name,
                    'date_submitted' => $rfi->created_at->format('m/d/y'),
                    'date_requested' => $rfi->due_date->format('m/d/y'),
                    'date_finalized' => $rfi->hasResponse() ? $rfi->response->created_at->format('m/d/y') : null,
                    'ball_in_court' => $rfi->ballInCourt,
                    'project_discipline_id' => $rfi->project_discipline_id,
                    'days_late' => $rfi->daysLate
                ];
            })->values(),
            'organization' => $organization,
            'project' => $project,
            'disciplines' => $disciplines->load('discipline', 'lead')->values(),
        ]);
    }
}
