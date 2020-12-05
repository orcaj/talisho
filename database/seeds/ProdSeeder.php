<?php

use App\Enum\Role;
use App\Organization;
use App\User;
use Illuminate\Database\Seeder;

class ProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DisciplineSeeder::class,
            EmployeeCountsSeeder::class,
            PublicQrSeeder::class,
            RolePermissionSeeder::class
        ]);

        \Illuminate\Support\Facades\Artisan::call('csi:import');
        \Illuminate\Support\Facades\Artisan::call('qr:internal:seed');
        \Illuminate\Support\Facades\Artisan::call('qr:public:generate');

        $org = Organization::create([
            'name' => 'Taliho',
            'address_1' => '123 Test Street',
            'address_2' => null,
            'city' => 'Troy',
            'state' => 'NY',
            'country' => 'US',
            'phone' => '518-123-4567',
            'website' => null,
            'account_type' => \App\Enum\AccountType::PAID,
            'account_status' => \App\Enum\AccountStatus::ACTIVE,
            'employee_count_id' => 1,
            'zip' => '12180',
            'primary_contact_name' => 'Todd Hesnor',
            'primary_contact_phone' => '518-123-4567',
            'primary_contact_email' => 'todd@taliho.com'
        ]);

        $user = User::create([
            'first_name' => 'Todd',
            'last_name' => 'Hesnor',
            'email' => 'todd@taliho.com',
            'organization_id' => $org->id,
        ]);

        $user->syncRoles([[Role::SUPER_ADMIN]]);
    }
}
