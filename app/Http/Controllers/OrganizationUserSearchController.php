<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Services\UserSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationUserSearchController extends Controller
{
    protected $service;

    public function __construct(UserSearchService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Organization $organization)
    {
        abort_unless(Auth::user()->organization->id === $organization->id, 403);

        return $this->service->searchOrganizationUsers($organization);
    }
}
