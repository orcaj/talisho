<?php

namespace App;


use App\Enum\Permission;
use App\Events\ConstructionDirectiveCreated;

class ConstructionDirective extends BaseFileableModel
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date',
    ];

    public function guests()
    {
        return $this->belongsToMany(User::class, 'construction_directive_guest', 'construction_directive_id', 'guest_user_id');
    }

    public function projectDiscipline()
    {
        return $this->belongsTo(ProjectDiscipline::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'from_lead_user_id');
    }

    public function reassignUser($oldUser, $newUser)
    {
        $this->guests()->detach($oldUser);

        if (! $this->guests->contains($newUser)) {
            $this->guests()->attach($newUser);
        }
    }

    public function belongsToProjectDiscipline(ProjectDiscipline $projectDiscipline)
    {
        return $this->projectDiscipline->id === $projectDiscipline->id;
    }

    public function isVisibleForUser($user)
    {
        return ($user->can(Permission::VIEW_ALL_COMMUNICATION) && $this->isAssociatedWithOrganization($user->organization))
            || $this->isAssociatedWithUser($user);
    }

    public function isAssociatedWithUser(User $user): bool
    {
        return $this->guests->contains($user) || $this->projectDiscipline->lead->id === $user->id;
    }

    public function isAssociatedWithOrganization(Organization $organization): bool
    {
        return $this->projectDiscipline->project->organization->id === $organization->id;
    }
}
