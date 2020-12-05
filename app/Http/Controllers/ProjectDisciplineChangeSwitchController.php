<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeSwitchRequest;
use App\ProjectDiscipline;
use Illuminate\Support\Facades\Redirect;

class ProjectDisciplineChangeSwitchController extends Controller
{
    public function __invoke(ChangeSwitchRequest $request, ProjectDiscipline $projectDiscipline)
    {
        $projectDiscipline->update([
            'documentation_status' => $request->validated()['documentation_status'],
            'communication_status' => $request->validated()['communication_status'],
            'liability_status' => $request->validated()['liability_status'],
        ]);


        return Redirect::back()->with('success', 'Switch has been changed');
    }
}
