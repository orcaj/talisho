<?php

namespace App\Http\Controllers;

use App\Enum\DocumentType;
use App\Http\Data\DocumentationData;
use App\Http\Requests\StoreOtherDocumentRequest;
use App\ProjectDiscipline;
use App\Services\CreateDocumentationService;
use App\User;
use Illuminate\Support\Facades\Redirect;

class CreateTabDocumentController extends Controller
{
    protected $service;

    public function __construct(CreateDocumentationService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreOtherDocumentRequest $request, ProjectDiscipline $projectDiscipline, User $user)
    {
        $this->service->createTabDocuments(
            new DocumentationData(
                $projectDiscipline,
                $request->user(),
                $user,
                $request->validated()['due_date'],
                $request->resolveSpecifications(DocumentType::TAB_CX_LEED)
            )
        );

        return Redirect::back()->with('success', 'Documents have been assigned to the user.');
    }
}
