<?php

namespace App\Policies;

use App\Enum\Permission;
use App\Enum\Role;
use App\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any organizations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can view the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
        return $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can create organizations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can update the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        if ($user->can(Permission::EDIT_ORGANIZATIONS)) {
            return $user->organization->id === $organization->id || $user->hasRole(Role::SUPER_ADMIN);
        }
    }

    /**
     * Determine whether the user can delete the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function restore(User $user, Organization $organization)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function forceDelete(User $user, Organization $organization)
    {
        return false;
    }
}
