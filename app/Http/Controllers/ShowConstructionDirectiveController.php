<?php

namespace App\Http\Controllers;

use App\Enum\Permission;
use App\Organization;
use App\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowConstructionDirectiveController extends Controller
{
    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $user = $request->user();
        $disciplines = $user->can(Permission::VIEW_ALL_COMMUNICATION)
            ? $project->disciplines
            : $project->disciplines->filter->isVisibleFor($user);

        return Inertia::render('Organization/Project/Communication/ConstructionDirective/Show.vue', [
            'communications' => $disciplines->pluck('constructionDirectives')->flatten()->filter->isVisibleForUser($user)->map(function ($directive) {
                return [
                    'id' => $directive->id,
                    'identifier' => $directive->identifier,
                    'subject' => $directive->subject,
                    'date' => $directive->created_at->format('m/d/y'),
                    'project_discipline_id' => $directive->project_discipline_id
                ];
            })->values(),
            'organization' => $organization,
            'project' => $project,
            'disciplines' => $disciplines->load('discipline', 'lead')->values(),
        ]);
    }
}
