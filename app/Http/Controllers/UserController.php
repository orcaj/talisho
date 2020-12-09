<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Organization;
use App\PaymentInfo;
use App\Services\UserService;
use App\User;
use App\ProjectDiscipline;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

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
        $user = User::withTrashed()->where('email', $request->data()['email'])->first();

        if ($user === null) {
            $user  = User::create($request->data())->assignRole(Role::USER);
            $user->save();
            $user->sendSetPasswordEmail($request->user());
        }

        $user->deleted_at = null;

        $user->save();

        if (array_key_exists('discipline', $request->data())) {
            $projectDiscipline = ProjectDiscipline::where('id', $request->data()['discipline'])->first();
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
            // 'company' => $user->company,
            'company' => $user->organization
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

    public function cancel_subscription()
    {
        $user = auth()->user();
        $sub = PaymentInfo::where('user_id', $user->id)->where('status', 'current')->first();
        try {
            if ($sub) {
                $sub->status = "cancel";
                $sub->save();
                $stripe_sk = config('services.stripe.sk');
                $stripe = new \Stripe\StripeClient($stripe_sk);
                $stripe->subscriptions->cancel(
                    $sub->sub_id,
                );
                $organization = Organization::find($user->organization_id);
                $organization->account_status = 'INACTIVE';
                $organization->save();
            }
            return redirect()->back()->with('success', 'Suceess');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'No such subscription');
        }
    }

    public function retry_subscription()
    {
        $user = auth()->user();
        $sub = PaymentInfo::where('user_id', $user->id)->first();
        try {
            if ($sub) {
                $customer_id = $sub->customer_id;

                $stripe_pk = config('services.stripe.sk');
                $stripe_price_id = config('services.stripe.price_id');
                $stripe = new \Stripe\StripeClient($stripe_pk);
                $subscription = $stripe->subscriptions->create([
                    'customer' => $customer_id,
                    'items' => [
                        ['price' => $stripe_price_id],
                    ],
                    'trial_end' => strtotime(Carbon::now()->addDay(14)->toDateString()),
                ]);

                PaymentInfo::create([
                    'user_id' => $user->id,
                    'sub_id' => $subscription['id'],
                    'customer_id' => $customer_id,
                    'status' => 'current'
                ]);
                $organization = Organization::find($user->organization_id);
                $organization->account_status = 'ACTIVE';
                $organization->save();
            }
            return redirect()->back()->with('success', 'Suceess');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'No such subscription');
        }
    }

}
