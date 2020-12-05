<?php

namespace App\Http\Controllers;

use App\DocumentationSubmission;
use App\Enum\DocumentationResponseStatus;
use App\Enum\MessagingStatus;
use App\Http\Requests\StoreDocumentationSubmissionResponseRequest;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Redirect;

class StoreDocumentationSubmissionResponseController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreDocumentationSubmissionResponseRequest $request, DocumentationSubmission $documentationSubmission)
    {
        $documentationSubmission->documentation->update([
            'due_date' => data_get($request->validated(), 'due_date'),
            'status' => DocumentationResponseStatus::collection()->firstWhere('label', $request->validated()['status'])['approves'] ? MessagingStatus::APPROVED : MessagingStatus::OUTSTANDING
        ]);

        $documentationSubmission->response()->create([
            'comment' => $request->validated()['comments'],
            'status' => $request->validated()['status'],
            'to_user_id' => $documentationSubmission->documentation->assigned->id,
            'from_user_id' => $documentationSubmission->documentation->lead->id
        ])->files()->createMany(
            $this->service->uploadFiles(data_get($request->validated(), 'files', []), $documentationSubmission->documentation->baseFilePath)
        );

        return Redirect::back()->with('success', 'Response successfully submitted!');
    }
}
