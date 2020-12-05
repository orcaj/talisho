<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRfiResponseRequest;
use App\ProjectDiscipline;
use App\Rfi;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Redirect;

class StoreRfiResponseController extends Controller
{
    protected $service;

    public function __construct(FileUploadService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreRfiResponseRequest $request, ProjectDiscipline $projectDiscipline, Rfi $rfi)
    {
        $rfi->response()->create([
            'response' => $request->validated()['response'],
            'from_lead_user_id' => $request->user()->id
        ])->files()->createMany(
            $this->service->uploadFiles(data_get($request->validated(), 'files', []), $projectDiscipline->rfiBasePath . '/' . $rfi->identifier)
        );

        return Redirect::back()->with('success', 'RFI response sent to guest.');
    }
}
