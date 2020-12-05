<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcknowledgeIncidentReportRequest;
use App\IncidentReport;
use App\ProjectDiscipline;
use Illuminate\Support\Facades\Redirect;

class AcknowledgeIncidentReportController extends Controller
{
    public function __invoke(AcknowledgeIncidentReportRequest $request, ProjectDiscipline $projectDiscipline, IncidentReport $incidentReport)
    {
        $incidentReport->update([
            'is_acknowledged' => $request->validated()['is_acknowledged']
        ]);

        return Redirect::back()->with('success', 'Updated Incident Report Acknowledgement');
    }
}
