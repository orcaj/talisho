<?php

use App\EmployeeCount;
use Illuminate\Database\Seeder;

class EmployeeCountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeCount::firstOrCreate([
            'label' => '1',
            'sort' => 1
        ]);

        EmployeeCount::firstOrCreate([
            'label' => '2-5',
            'sort' => 2
        ]);

        EmployeeCount::firstOrCreate([
            'label' => '6-10',
            'sort' => 3
        ]);

        EmployeeCount::firstOrCreate([
            'label' => '11-25',
            'sort' => 4
        ]);

        EmployeeCount::firstOrCreate([
            'label' => '26-50',
            'sort' => 5
        ]);

        EmployeeCount::firstOrCreate([
            'label' => '51+',
            'sort' => 6
        ]);
    }
}
