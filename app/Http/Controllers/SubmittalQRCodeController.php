<?php

namespace App\Http\Controllers;

use App\Project;
use App\QrCSI;
use App\Services\QRDocumentationMappingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmittalQRCodeController extends Controller
{
    protected $service;

    public function __construct(QRDocumentationMappingService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Project  $project)
    {
        $this->authorize('view', $project);

        $disciplines = $project->disciplines;

        $submittals = $this->service->mapSubmittalsForQr($project);

        return Inertia::render('Organization/Project/QR/Index', [
            'project' => $project,
            'title' => 'Approved Submittals',
            'projectDisciplines' => $disciplines->load('team', 'discipline', 'lead.organization')->values(),
            'tableData' => $disciplines->mapToGroups(function ($disc) use ($submittals) {
                return [
                    $disc->id => $submittals->where('project_discipline_id', $disc->id)->values()
                ];
            })
        ]);
    }
}
