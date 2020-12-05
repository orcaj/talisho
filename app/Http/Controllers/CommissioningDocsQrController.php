<?php

namespace App\Http\Controllers;

use App\Project;
use App\Services\QRDocumentationMappingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommissioningDocsQrController extends Controller
{
    protected $service;

    public function __construct(QRDocumentationMappingService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $disciplines = $project->disciplines;

        $documents = $this->service->mapCommissioningDocumentsForQr($project);

        return Inertia::render('Organization/Project/QR/Index', [
            'project' => $project,
            'title' => 'Commissioning Documentation',
            'projectDisciplines' => $disciplines->load('team', 'discipline', 'lead.organization')->values(),
            'tableData' => $disciplines->mapToGroups(function ($disc) use ($documents) {
                return [
                    $disc->id => $documents->where('project_discipline_id', $disc->id)->values()
                ];
            })
        ]);
    }
}
