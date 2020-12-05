<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

class CustomSpecificationTabCxLeedController extends Controller
{
    public function __invoke(Request $request, Organization $organization)
    {
        return $organization->customSpecifications()->tabCxLeed()->get();
    }
}
