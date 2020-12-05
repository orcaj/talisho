<?php

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $org1 = factory(\App\Organization::class)->create();
        $org2 = factory(\App\Organization::class)->create();
        $org3 = factory(\App\Organization::class)->create();
        $org4 = factory(\App\Organization::class)->create();
        $org5 = factory(\App\Organization::class)->create();

        factory(\App\Project::class, 10)->create([
            'organization_id' => $org1->id
        ]);

        factory(\App\Project::class, 10)->create([
            'organization_id' => $org2->id
        ]);

        factory(\App\Project::class, 10)->create([
            'organization_id' => $org3->id
        ]);

        factory(\App\Project::class, 10)->create([
            'organization_id' => $org4->id
        ]);

        factory(\App\Project::class, 10)->create([
            'organization_id' => $org5->id
        ]);
    }
}
