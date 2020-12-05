<?php

namespace App\Http\Controllers;

use App\Enum\DocumentType;
use App\Organization;
use App\Project;
use App\Services\ShowDocumentationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowSubmittalsController extends Controller
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
            'documentType' => DocumentType::SUBMITTAL,
            'documents' => $this->service->getSubmittalsForDisciplines($disciplines)
                ->filter(function ($doc) use ($request) {
                    return $doc->documentation->isVisibleForUser($request->user());
                })
                ->pipe(function ($collection) {
                    return $this->service->mapDocumentDataForDisplay($collection);
                })
        ]);
    }

}
