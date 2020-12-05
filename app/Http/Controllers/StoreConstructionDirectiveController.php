<?php

namespace App\Http\Controllers;

use App\Events\ConstructionDirectiveCreated;
use App\Http\Requests\StoreConstructionDirectiveRequest;
use App\ProjectDiscipline;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Redirect;

class StoreConstructionDirectiveController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreConstructionDirectiveRequest $request, ProjectDiscipline $projectDiscipline)
    {
        $identifier = $projectDiscipline->generateIdentifier($projectDiscipline->constructionDirectives->count());

        $directive = $projectDiscipline->constructionDirectives()->create([
            'identifier' => $identifier,
            'subject' => $request->validated()['subject'],
            'drawing_number' => $request->validated()['drawing_number'],
            'specification_number' => $request->validated()['specification_number'],
            'directive' => $request->validated()['directive'],
            'from_lead_user_id' => $request->user()->id,
        ]);

        $directive->guests()->attach($request->validated()['guests']);

        // need to wait for guests to be attached before firing event
        event(new ConstructionDirectiveCreated($directive));

        $directive->files()->createMany(
            $this->service->uploadFiles(
                data_get($request->validated(), 'files', []), $projectDiscipline->constructionDirectiveBasePath . '/' . $identifier
            )
        );

        return Redirect::back()->with('success', 'Construction Directive sent to guest(s).');
    }
}
