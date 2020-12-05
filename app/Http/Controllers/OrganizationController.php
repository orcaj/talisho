<?php

namespace App\Http\Controllers;

use App\Discipline;
use App\EmployeeCount;
use App\Http\Requests\Organizations\UpdateOrganizationRequest;
use App\Organization;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class OrganizationController extends Controller
{
    public function edit(Organization $organization)
    {
        // TODO refactor this data fetching to some sort of reusable cache implementation

        $this->authorize('update', $organization);

        return Inertia::render('Organization/Edit', [
            'organization' => $organization->load('disciplines', 'employeeCount'),
            'disciplines' => Discipline::all(),
            'employeeCounts' => EmployeeCount::all()->sortBy('sort'),
            'states' => collect(json_decode(file_get_contents(resource_path('data/states.json'))))->all(),
            'countries' => collect(json_decode(file_get_contents(resource_path('data/countries.json'))))->sort()->all(),
        ]);
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $organization->disciplines()->sync($request->disciplines);

        $organization->update($request->validated());

        return Redirect::back()->with('success', 'Organization Updated');
    }
}
