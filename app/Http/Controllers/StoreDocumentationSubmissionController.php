<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Enum\MessagingStatus;
use App\Http\Requests\StoreDocumentationSubmissionRequest;
use App\Services\FileUploadService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;

class StoreDocumentationSubmissionController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreDocumentationSubmissionRequest $request, Documentation $documentation)
    {
        $documentation->update([
            'due_date' => Carbon::parse($request->validated()['due_date']),
            'status' => MessagingStatus::UNDER_REVIEW
        ]);

        $documentation->submissions()->create([
            'comment' => $request->validated()['comments'],
            'to_user_id' => $documentation->lead->id,
            'from_user_id' => $documentation->assigned->id
        ])->files()->createMany(
            $this->service->uploadFiles(data_get($request->validated(), 'files', []), $documentation->baseFilePath)
        );

        return Redirect::back()->with('success', 'Documentation response submitted!');
    }
}
