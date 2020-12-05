<?php


namespace App\Services;

use App\Documentation;
use App\DocumentationResponse;
use App\DocumentationSubmission;
use App\Enum\DocumentationResponseStatus;
use App\Http\Data\ManualApprovalData;
use App\Submittal;
use Illuminate\Support\Facades\Auth;

class ManualApprovalService
{
    public $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function handle(Documentation $documentation, ManualApprovalData $data): void
    {
        if ($documentation->submissions()->exists() && ! $this->everyDocumentationSubmissionHasAResponse($documentation)) {
            $submission = $documentation->submissions()->whereDoesntHave('response')->first();
            $this->createDocumentationResponse($documentation, $submission, $data);
            $documentation->markAsApprovedByUpload();
            return;
        }

        $newSubmission = $this->createPlaceholderSubmission($documentation);
        $this->createDocumentationResponse($documentation, $newSubmission, $data);

        $documentation->markAsApprovedByUpload();
    }

    protected function createDocumentationResponse(Documentation $documentation, DocumentationSubmission $submission, ManualApprovalData $data): DocumentationResponse
    {
        $newResponse = DocumentationResponse::create([
            'comment' => $data->getComments(),
            'status' => $documentation->entity_type === Submittal::class ? DocumentationResponseStatus::NO_EXCEPTIONS_TAKEN['label'] : DocumentationResponseStatus::ACCEPTED['label'],
            'to_user_id' => Auth::id(),
            'from_user_id' => Auth::id(),
            'documentation_submission_id' => $submission->id
        ]);

        $newResponse->files()->createMany(
            $this->fileService->uploadFiles($data->getFiles(), $documentation->baseFilePath)
        );

        return $newResponse;
    }

    protected function createPlaceholderSubmission(Documentation $documentation): DocumentationSubmission
    {
        return DocumentationSubmission::create([
            'comment' => 'Bypassed by Manual Upload',
            'to_user_id' => Auth::id(),
            'from_user_id' => Auth::id(),
            'documentation_id' => $documentation->id
        ]);
    }

    protected function everyDocumentationSubmissionHasAResponse(Documentation $documentation): bool
    {
        return $documentation->submissions->every(function (DocumentationSubmission $submission) {
            return $submission->response()->exists();
        });
    }
}
