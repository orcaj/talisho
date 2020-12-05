<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemoveTeamUserRequest;
use App\ProjectDiscipline;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProjectDisciplineGuestController extends Controller
{
    public function create(Request $request, ProjectDiscipline $projectDiscipline, User $user)
    {
        abort_unless($projectDiscipline->isLeadBy($request->user()), 403);

        //abort_if($user->belongsToTeamInProject($projectDiscipline->project), 422);

        if ($projectDiscipline->team()->doesntExist()) {
            $projectDiscipline->active_at = today();
            $projectDiscipline->save();
        }

        $projectDiscipline->team()->attach($user);

        return Redirect::back()->with('success', 'Guest successfully added to project!');
    }

    public function update(RemoveTeamUserRequest $request, ProjectDiscipline $projectDiscipline, User $user)
    {
        abort_unless($projectDiscipline->isLeadBy($request->user()), 403);

        $newUser = User::find($request->validated()['new_user_id']);

        // reassign Documents
        $user->documentation->load('entity')->filter(function ($doc) use ($projectDiscipline) {
            return $doc->entity->project_discipline_id === $projectDiscipline->id;
        })->each->reassignUser($newUser);

        // reassign rfis
        $user->rfis->filter->belongsToProjectDiscipline($projectDiscipline)->each->reassignUser($newUser);

        // reassign incident reports
        $user->incidentReports->filter->belongsToProjectDiscipline($projectDiscipline)->each->reassignUser($newUser);

        // reassign Construction Directives
        $user->constructionDirectives->filter->belongsToProjectDiscipline($projectDiscipline)->each->reassignUser($user, $newUser);

        // replace in team
        $projectDiscipline->replaceUser($user, $newUser);

        return Redirect::back()->with('success', 'User removed and documents have been re-assigned');
    }

    public function resend(Request $request, ProjectDiscipline $projectDiscipline, User $user)
    {
        abort_unless($projectDiscipline->isLeadBy($request->user()), 403);

        $user ->sendSetPasswordEmail($request->user());

        return Redirect::back()->with('success', 'Successfully to resent Invitation Email.');
    }
}
