<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             DisciplineSeeder::class,
             EmployeeCountsSeeder::class,
             UserSeeder::class,
             PublicQrSeeder::class,
         ]);
    }
}
