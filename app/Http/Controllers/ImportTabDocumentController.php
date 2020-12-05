<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportOtherDocumentRequest;
use App\ProjectDiscipline;
use App\Services\ImportDocumentationService;
use App\User;
use Illuminate\Support\Facades\Redirect;

class ImportTabDocumentController extends Controller
{
    protected $service;

    public function __construct(ImportDocumentationService $service)
    {
        $this->service = $service;
    }

    public function __invoke(ImportOtherDocumentRequest $request, ProjectDiscipline $projectDiscipline)
    {
        $result  = $this->service->importTabDocuments(
                $projectDiscipline,
                $request->validated()['old_project_id']);

        if ($result['status'])
            return Redirect::back()->with('success', 'Documents have been assigned to the user.');
        else
            return Redirect::back()->with('error', $result['message']);
    }
}
