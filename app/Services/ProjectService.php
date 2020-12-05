<?php

namespace App\Services;

use App\Http\Requests\Projects\SaveProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Organization;
use App\Project;
use App\ProjectDiscipline;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function createProject(SaveProjectRequest $request, Organization $organization) {
        return DB::transaction(function () use ($request, $organization) {
            $project = Project::create($this->projectRequestMapping($request, $organization));
            $this->saveDisciplines($request, $organization, $project);
            $this->saveDefaults($request, $organization);

            return $project;
        });
    }

    public function updateProject(UpdateProjectRequest $request, Organization $organization, Project $project) {
        DB::transaction(function() use ($request, $organization, $project) {
            $project->offsetUnset('tabStatuses');
            $project->update($this->projectRequestMapping($request, $organization));
        });
    }

    protected function saveDisciplines(SaveProjectRequest $request, Organization $organization, Project $project) {
        foreach ($request->getSelectedDisciplines() as $disciplineDatum) {
            $project->disciplines()->save(new ProjectDiscipline([
                'project_id' => $project->id,
                'discipline_id' => $disciplineDatum['id'],
                'user_id' => $disciplineDatum['lead']['id'],
            ]));
        }
    }

    protected function saveDefaults(SaveProjectRequest $request, Organization $organization) {
        if ($request->getSaveDefaults()) {
            $organization->projectDefaultDisciplines()->sync(array_column($request->getSelectedDisciplines(), 'id'));
        }
    }

    protected function projectRequestMapping($request, Organization $organization): array {
        return [
            'name' => $request->getProjectName(),
            'address_1' => $request->getStreet(),
            'city' => $request->getCity(),
            'state' => $request->getState(),
            'zip' => $request->getZip(),
            'country' => 'US',
            'client_name' => $request->getClientName(),
            'description' => $request->getDescription(),
            'organization_id' => $organization->id,
        ];
    }
}
