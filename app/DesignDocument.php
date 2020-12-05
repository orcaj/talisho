<?php

namespace App;


use App\Enum\Permission;

class DesignDocument extends BaseFileableModel
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function isAssociatedWithUser($user): bool
    {
        return $user->belongsToTeamInProject($this->project)
            || $user->leadsDisciplineInProject($this->project)
            || ($user->can(Permission::VIEW_ALL_PROJECT_FILES) && $this->isAssociatedWithOrganization($user->organization));
    }

    public function isAssociatedWithOrganization($organization): bool
    {
        return $this->project->organization->id === $organization->id;
    }
}
