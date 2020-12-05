<?php

namespace App\Policies;

use App\Enum\Permission;
use App\Enum\Role;
use App\Organization;
use App\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any projects.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Organization $organization)
    {
        if (!$user->hasRole(Role::SUPER_ADMIN)) {
            return $user->organization->id === $organization->id;
        }
    }

    /**
     * Determine whether the user can view the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        if ($user->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }

        if ($user->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES) && $user->organization->id === $project->organization->id) {
            return true;
        }

        if ($user->leadsDisciplineInProject($project)) {
            return true;
        }

        if ($user->belongsToTeamInProject($project)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {
        if ($user->can(Permission::CREATE_PROJECTS)) {
            return $user->organization->id === $organization->id;
        }
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        if ($user->can(Permission::EDIT_PROJECTS) && !$user->hasRole(Role::SUPER_ADMIN)) {
            return $user->organization->id === $project->organization->id;
        }
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function restore(User $user, Project $project)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function forceDelete(User $user, Project $project)
    {
        return false;
    }
}
