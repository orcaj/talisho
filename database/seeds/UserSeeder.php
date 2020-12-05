<?php

use App\Enum\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $user = User::firstOrCreate(
            [
                'email' => 'todd@taliho.com',
            ],
            [
                'first_name' => 'Todd',
                'last_name' => 'Hesnor',
                'password' => bcrypt('password'),
                'organization_id' => factory(\App\Organization::class)->create()->id,
                'mobile_phone' => '555-555-5555',
            ]
        );
        $user->syncRoles([Role::SUPER_ADMIN]);

        $user = User::firstOrCreate(
            [
                'email' => 'chris.boyle@troyweb.com',
            ],
            [
                'first_name' => 'Chris',
                'last_name' => 'Boyle',
                'password' => bcrypt('password'),
                'organization_id' => factory(\App\Organization::class)->create()->id,
                'mobile_phone' => '555-555-5555',
            ]
        );
        $user->syncRoles([Role::SUPER_ADMIN]);

        $user = User::firstOrCreate(
            [
                'email' => 'nick.pakatar@troyweb.com',
            ],
            [
                'first_name' => 'Nick',
                'last_name' => 'Pakatar',
                'password' => bcrypt('password'),
                'organization_id' => factory(\App\Organization::class)->create()->id,
                'mobile_phone' => '555-555-5555',
            ]);

        $user->syncRoles([Role::SUPER_ADMIN]);

        $user = User::firstOrCreate(
            [
                'email' => 'mike@troyweb.com',
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Pacella',
                'password' => bcrypt('password'),
                'organization_id' => factory(\App\Organization::class)->create()->id,
                'mobile_phone' => '555-555-5555',
            ]);

        $user->syncRoles([Role::SUPER_ADMIN]);

        $user = User::firstOrCreate(
            [
                'email' => 'karen.grodnick@troyweb.com',
            ],
            [
                'first_name' => 'Karen',
                'last_name' => 'Grodnick',
                'password' => bcrypt('password'),
                'organization_id' => factory(\App\Organization::class)->create()->id,
                'mobile_phone' => '555-555-5555',
            ]);

        $user->syncRoles([Role::SUPER_ADMIN]);
    }
}
