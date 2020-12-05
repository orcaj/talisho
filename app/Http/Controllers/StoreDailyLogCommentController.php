<?php

namespace App\Http\Controllers;

use App\DailyLog;
use App\Http\Requests\StoreDailyLogCommentRequest;
use App\ProjectDiscipline;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Redirect;

class StoreDailyLogCommentController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreDailyLogCommentRequest $request, ProjectDiscipline $projectDiscipline, DailyLog $dailyLog)
    {
        $dailyLog->comments()->create([
            'comment_by_user_id' => $request->user()->id,
            'comment' => $request->validated()['comment'],
        ])->files()->createMany(
            $this->service->uploadFiles(data_get($request->validated(), 'files', []), $dailyLog->projectDiscipline->dailyLogBasePath . '/' . $dailyLog->identifier)
        );

        return Redirect::back()->with('success', 'Comment has been added to the Daily Log');
    }
}
