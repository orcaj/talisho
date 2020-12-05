<?php

namespace App\Http\Controllers;

use App\Http\Data\DocumentationData;
use App\Http\Requests\StoreSubmittalRequest;
use App\ProjectDiscipline;
use App\Services\CreateDocumentationService;
use App\User;
use Illuminate\Support\Facades\Redirect;

class CreateSubmittalController extends Controller
{
    protected $service;

    public function __construct(CreateDocumentationService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreSubmittalRequest $request, ProjectDiscipline $projectDiscipline, User $user)
    {
        $this->service->createSubmittal(
            new DocumentationData(
                $projectDiscipline,
                $request->user(),
                $user,
                $request->validated()['due_date'],
                $request->resolveAssociatedDocumentsForSubmittal(),
                $request->resolveSpecificationForSubmittal()
            )
        );

        return Redirect::back()->with('success', 'Documents have been assigned to the user.');
    }
}
