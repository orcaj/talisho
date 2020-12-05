<?php

namespace App\Http\Controllers;

use App\Enum\DocumentType;
use App\Organization;
use App\Project;
use App\Services\ShowDocumentationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowTabCxLeedController extends Controller
{
    protected $service;

    public function __construct(ShowDocumentationService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $disciplines = $this->service->getProjectDisciplinesForUser($project, $request->user());
        return Inertia::render('Organization/Project/Documentation/Show.vue', [
            'project' => $project,
            'organization' => $organization,
            'disciplines' => $disciplines->values(),
            'documentType' => DocumentType::TAB_CX_LEED,
            'documents' => $this->service->getOtherDocumentsByTypeForDisciplines($disciplines, DocumentType::TAB_CX_LEED)
                ->filter(function ($otherDoc) use ($request) {
                    return $otherDoc->documentation->isVisibleForUser($request->user());
                })
                ->pipe(function ($collection) {
                    return $this->service->mapDocumentDataForDisplay($collection);
                })
        ]);
    }
}
