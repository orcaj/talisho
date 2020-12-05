<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Services\UserService;
use App\User;
use App\ProjectDiscipline;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;

        $this->authorizeResource(User::class);
    }

    public function create()
    {
        return Inertia::render('User/Create');
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::withTrashed()->where('email', $request -> data()['email'])->first();

        if ($user === null) {
            $user  = User::create($request->data())->assignRole(Role::USER);
            $user -> save();
            $user ->sendSetPasswordEmail($request->user());
        }

        $user -> deleted_at = null;

        $user -> save();

        if (array_key_exists('discipline', $request -> data())) {
            $projectDiscipline = ProjectDiscipline::where('id',$request -> data()['discipline']) -> first();
            if ($projectDiscipline->team()->doesntExist()) {
                $projectDiscipline->active_at = today();
                $projectDiscipline->save();
            }
            $projectDiscipline->team()->attach($user);
            return Redirect::back()->with('success', 'Guest successfully added to project!');
        }


        return redirect()->route('organizations.users.index', ['organization' => $request->user()->organization->id]);
    }

    public function edit(User $user)
    {
        return Inertia::render('User/Edit', [
            'user' => $user,
            'organization' => $user->organization,
            'company' => $user->company
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->service->update($request, $user);

        return Redirect::back()->with('success', 'Profile updated!');
    }

    public function destroy(User $user)
    {
        $pmId = $user->organization->projectManager->id;

        // if user leads disciplines, replace lead with PM on deletion
        if ($user->isALead()) {
            $user->leadDisciplines()->update([
                'user_id' => $pmId
            ]);
        }

        if ($user->leadDocumentation()->exists()) {
            $user->leadDocumentation()->update([
                'lead_user_id' => $pmId
            ]);
        }

        // reassign Documents
        $user->documentation->load('entity')->each(function ($doc) {
            $doc->reassignUser($doc->lead, false);
        });

        // reassign rfis
        $user->rfis->each(function ($rfi) {
            $rfi->reassignUser($rfi->projectDiscipline->lead);
        });

        // reassign incident reports
        $user->incidentReports->each(function ($report) {
            $report->reassignUser($report->projectDiscipline->lead);
        });

        // reassign Construction Directives
        $user->constructionDirectives->each(function ($directive) use ($user) {
            $directive->reassignUser($user, $directive->projectDiscipline->lead);
        });

        //// remove from teams
        $user->teams()->detach();

        $user->delete();

        return Redirect::back()->with('success', 'Employee removed.');
    }
}
