<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeLeadRequest;
use App\ProjectDiscipline;
use Illuminate\Support\Facades\Redirect;

class ProjectDisciplineChangeLeadController extends Controller
{
    public function __invoke(ChangeLeadRequest $request, ProjectDiscipline $projectDiscipline)
    {
        $projectDiscipline->update([
            'user_id' => $request->validated()['lead_id']
        ]);

        $projectDiscipline->otherDocuments->pluck('documentation')->each->changeLead($request->validated()['lead_id']);

        $projectDiscipline->submittals->pluck('documentation')->each->changeLead($request->validated()['lead_id']);

        return Redirect::back()->with('success', 'Discipline Lead has been changed');
    }
}
