<?php

namespace App\Http\Controllers;

use App\CSI;
use App\CSIDivision;
use App\CSIQuickList;
use App\Enum\Permission;
use App\Organization;
use App\Project;
use App\Services\ProjectDisciplineStatusesService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectDocumentationIndexController extends Controller
{
    protected $service;

    public function __construct(ProjectDisciplineStatusesService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $auth = $request->user();
        $disciplines = $auth->can(Permission::VIEW_ALL_DOCUMENTATION)
            ? $project->disciplines
            : $project->disciplines->filter->isVisibleFor($auth);

        return Inertia::render('Organization/Project/Documentation/Index', [
            'organization' => $organization,
            'project' => $project,
            'projectsList' => $request->user()->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES) ? $organization -> projects() -> where('id','!=', $project -> id) -> get() : [],
            'projectDisciplinesWithTeams' => $disciplines->load('team', 'discipline', 'lead')->flatten(),
            'quickLists' => CSIQuickList::with('csi')->get()->mapToGroups(function (CSIQuickList $quickListItem) {
                return [$quickListItem->quick_list => $quickListItem->csi];
            }),
            'idsOfCSIsInProject' => $project->otherDocuments()->where('specification_type', '=', CSI::class)->pluck('specification_id')->merge(
                $project->submittals()->where('specification_type', '=', CSI::class)->pluck('specification_id')
            ),
            'submittalDivisions' => CSIDivision::submittals()->get(),
            'info' => $this->service->mapDisciplinesToSummaryData($disciplines, request()->user())
        ]);
    }
}
