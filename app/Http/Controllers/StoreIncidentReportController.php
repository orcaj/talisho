<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncidentReportRequest;
use App\ProjectDiscipline;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Redirect;

class StoreIncidentReportController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreIncidentReportRequest $request, ProjectDiscipline $projectDiscipline)
    {
        $identifier = $projectDiscipline->generateIdentifier($projectDiscipline->incidentReports->count());
        $projectDiscipline->incidentReports()->create([
            'incident_date' => $request->validated()['incident_date'],
            'reported_by_user_id' => $request->user()->id,
            'assigned_to_user_id' => $request->user()->id,
            'information'=> json_decode($request->validated()['information']),
            'identifier' => $identifier,
            'is_acknowledged' => false
        ])->files()->createMany(
            $this->service->uploadFiles(data_get($request->validated(), 'files', []), $projectDiscipline->incidentReportBasePath . '/' . $identifier)
        );

        return Redirect::back()->with('success', 'Incident Report has been submitted');
    }
}
