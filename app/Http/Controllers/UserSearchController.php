<?php

namespace App\Http\Controllers;

use App\Services\UserSearchService;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{

    protected $service;

    public function __construct(UserSearchService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request)
    {
        return $this->service->searchAllUsers();
    }
}
