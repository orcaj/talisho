<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganizationUserController extends Controller
{
    public function __invoke(Request $request, Organization $organization)
    {
        $this->authorize('viewAny', [User::class, $organization]);

        return Inertia::render('Organization/User/Index', [
            'users' => function () use ($organization, $request) {
                return User::where('organization_id', $organization->id)
                    ->orderBy('welcome_valid_until', 'desc')
                    ->orderBy('last_name')
                    ->get();
            },
            'organization' => $organization
        ]);
    }
}
