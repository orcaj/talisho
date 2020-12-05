<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Enum\DocumentType;
use App\Http\Data\DocumentationData;
use App\Http\Requests\AddAdditionalDocumentsToSubmittalRequest;
use App\Services\CreateDocumentationService;
use Illuminate\Support\Facades\Redirect;

class AddAdditionalDocumentsToSubmittalsController extends Controller
{
    protected $service;

    public function __construct(CreateDocumentationService $service)
    {
        $this->service = $service;
    }

    public function __invoke(AddAdditionalDocumentsToSubmittalRequest $request, Documentation $documentation)
    {
        $data = new DocumentationData(
            $documentation->entity->projectDiscipline,
            $documentation->entity->projectDiscipline->lead,
            $documentation->entity->projectDiscipline->lead,
            null,
            $request->resolveAssociatedDocumentsForSubmittal(),
        );

        $this->service->createOtherDocuments($data, DocumentType::SUBMITTAL , $documentation->entity);

        return Redirect::back()->with('success', 'Additional Associated Documents added successfully!');
    }
}
