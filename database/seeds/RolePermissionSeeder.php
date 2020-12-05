<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        //Permission::create(['name' => 'foo]);
        $role = Role::firstOrCreate(['name' => \App\Enum\Role::SUPER_ADMIN]);

        // As long as we stick to the Gate::before implementation we shouldn't need to explicitly assign Super Admin permissions

        $pmRole = Role::firstOrCreate(['name' => \App\Enum\Role::PROJECT_MANAGER]);

        $pmRole->syncPermissions([
            Permission::firstOrCreate(['name' => \App\Enum\Permission::VIEW_PROJECT_DISCIPLINE_COMMENTS]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::EDIT_ORGANIZATIONS]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::EDIT_PROJECTS]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::CREATE_PROJECTS]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::VIEW_ALL_PROJECT_DISCIPLINES]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::DELETE_EMPLOYEES]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::VIEW_EMPLOYEES]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::CREATE_EMPLOYEES]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::VIEW_ALL_DOCUMENTATION]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::VIEW_ALL_PROJECT_FILES]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::VIEW_ALL_COMMUNICATION]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::VIEW_ALL_LIABILITY]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::CREATE_DESIGN_DOCUMENTS]),
            Permission::firstOrCreate(['name' => \App\Enum\Permission::AUTO_APPROVE_DOCUMENTS]),
        ]);

        $role = Role::firstOrCreate(['name' => \App\Enum\Role::USER]);
        /*
        $role->givePermissionTo([
        ]);
        */
    }
}
