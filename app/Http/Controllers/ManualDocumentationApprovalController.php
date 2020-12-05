<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Http\Data\ManualApprovalData;
use App\Http\Requests\ManualApprovalRequest;
use App\Services\ManualApprovalService;
use Illuminate\Support\Facades\Redirect;

class ManualDocumentationApprovalController extends Controller
{
    public $service;

    public function __construct(ManualApprovalService $service)
    {
        $this->service = $service;
    }

    public function __invoke(ManualApprovalRequest $request, Documentation $documentation)
    {
        abort_if($documentation->isApproved, 419);

        $this->service->handle(
            $documentation,
            new ManualApprovalData(
                data_get($request->validated(), 'files', []),
                $request->validated()['comments']
            )
        );

        return Redirect::back()->with('success', 'Documentation has been marked as Approved.');
    }
}
