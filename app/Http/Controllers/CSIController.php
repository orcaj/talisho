<?php

namespace App\Http\Controllers;

use App\CSIQuickList;
use App\Project;
use App\CSI;
use App\QrCSI;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CSIController extends Controller
{
    public function __invoke(Request $request, Project $project, CSI $csi)
    {
        $this->authorize('view', $project);

        $disciplines = $project->disciplines;

        $isAssociatedDocument = CSIQuickList::associatedDocuments()->where('csi_id', $csi->id)->exists();

        $documents = $csi->getDocumentsForProject($project, $isAssociatedDocument);

        return Inertia::render('Organization/Project/QR/Index', [
            'project' => $project,
            'title' => QrCSI::firstWhere('csi_id', $csi->id)->name,
            'projectDisciplines' => $disciplines->load('team', 'discipline', 'lead.organization')->values(),
            'tableData' => $disciplines->mapToGroups(function ($disc) use ($documents) {
                return [
                    $disc->id => $documents->where('project_discipline_id', $disc->id)->values()
                ];
            })
        ]);
    }
}
