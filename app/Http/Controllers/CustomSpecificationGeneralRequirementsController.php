<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

class CustomSpecificationGeneralRequirementsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Organization $organization)
    {
        return $organization->customSpecifications()->generalRequirements()->get();
    }
}
