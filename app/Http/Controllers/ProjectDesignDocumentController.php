<?php

namespace App\Http\Controllers;

use App\DesignDocument;
use App\Enum\DesignDocumentType;
use App\Http\Requests\StoreDesignDocumentRequest;
use App\Http\Requests\UpdateDesignDocumentRequest;
use App\Organization;
use App\Project;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProjectDesignDocumentController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        return Inertia::render('Organization/Project/DesignDocuments/Index', [
            'organization' => $organization,
            'project' => function () use ($project) {
                return $project;
            },
            'types' => DesignDocumentType::singular(),
            'documents' => $project->designDocuments->load('files')->sortByDesc('created_at')->groupBy('type'),
            'disciplines' => $project->disciplines->load('discipline', 'lead.organization')
        ]);
    }

    public function store(StoreDesignDocumentRequest $request, Organization $organization, Project $project)
    {
        $project->designDocuments()->create([
            'name' => $request->validated()['name'],
            'type' => $type = $request->validated()['type']
        ])->files()->createMany(
            $this->service->uploadFiles(data_get($request->validated(), 'files', []), $project->designDocumentBaseFilePath . DesignDocumentType::getPluralizedFromSingular($type))
        );

        return Redirect::back()->with('success', "{$type} successfully uploaded");
    }

    public function update(UpdateDesignDocumentRequest $request, Organization $organization, Project $project, DesignDocument $designDocument)
    {
        $designDocument->update([
            'name' => $request->validated()['name']
        ]);

        return Redirect::back()->with('success', "Name has been updated");
    }

    public function destroy(Request $request, Organization $organization, Project $project, DesignDocument $designDocument)
    {
        $user = $request->user();
        abort_unless($user->isProjectManagerForOrganization($organization) || $user->leadsDisciplineInProject($project), 403);

        $designDocument->delete();

        return Redirect::back()->with('success', "Design Document has been deleted.");
    }
}
