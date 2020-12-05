<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyLogRequest;
use App\ProjectDiscipline;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class StoreDailyLogController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreDailyLogRequest $request, ProjectDiscipline $projectDiscipline)
    {
        $identifier = $projectDiscipline->generateIdentifier($projectDiscipline->dailyLogs->count());
        $projectDiscipline->dailyLogs()->create([
            'log_date' => $request->validated()['log_date'],
            'reported_by_user_id' => $request->user()->id,
            'information' => json_decode($request->validated()['information']),
            'identifier' => $identifier
        ])->files()->createMany(
            $this->service->uploadFiles(data_get($request->validated(), 'files', []), $projectDiscipline->dailyLogBasePath . '/' . $identifier)
        );

        return Redirect::back()->with('success', 'Daily Log has been submitted');
    }
}
