<?php


namespace Tests\SetupHelpers;

use App\Enum\Role;
use App\Organization;
use App\User;

class UserWithRoleGenerator
{
    protected $organization;
    protected $registered = true;

    public function forOrganization(Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    public function unregistered()
    {
        $this->registered = false;

        return $this;
    }

    public function createProjectManager()
    {
        return $this->user()->assignRole(Role::PROJECT_MANAGER);
    }

    public function createSuperAdmin()
    {
        return $this->user()->assignRole(Role::SUPER_ADMIN);
    }

    public function createUser()
    {
        return $this->user()->assignRole(Role::USER);
    }

    protected function user()
    {
        $factory = factory(User::class);

        if($this->registered) {
            $factory->state('registered');
        }

        return $factory->create([
            'organization_id' => $this->organization ?? factory(Organization::class),
        ]);
    }
}
