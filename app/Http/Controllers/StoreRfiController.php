<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRfiRequest;
use App\ProjectDiscipline;
use App\Services\FileUploadService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;

class StoreRfiController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreRfiRequest $request, ProjectDiscipline $projectDiscipline)
    {
        $identifier =  $projectDiscipline->generateIdentifier($projectDiscipline->rfis->count());
        $projectDiscipline->rfis()->create([
            'subject' => $request->validated()['subject'],
            'guest_user_id' => $request->user()->id,
            'created_by_user_id' => $request->user()->id,
            'drawing_number' => $request->validated()['drawing_number'],
            'specification_number' => $request->validated()['specification_number'],
            'due_date' => Carbon::parse($request->validated()['due_date']),
            'question' => $request->validated()['question'],
            'identifier' => $identifier
        ])->files()->createMany(
            $this->service->uploadFiles(data_get($request->validated(), 'files', []), $projectDiscipline->rfiBasePath . '/' . $identifier)
        );

        return Redirect::back()->with('success', 'RFI sent to lead.');
    }
}
