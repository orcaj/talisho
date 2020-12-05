<?php

use App\Discipline;
use Illuminate\Database\Seeder;

class DisciplineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for all these we will search for Discipline by label if discipline exists, and update the existing record that exists with the appropriate abbreviation and sort
        // if the seeder is run for the first time, the record is created with the appropriate label and abbreviation, and update just adds the sort priority
        Discipline::firstOrCreate(
            [
                'label' => 'Architectural',
            ],
            [
                'abbreviation' => 'A'
            ]
        )->update([
            'abbreviation' => 'A',
            'sort' => 1
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Interior Design',
            ],
            [
                'abbreviation' => 'ID'
            ]
        )->update([
            'abbreviation' => 'ID',
            'sort' => 2
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Landscape',
            ],
            [
                'abbreviation' => 'LAND'
            ]
        )->update([
            'abbreviation' => 'LAND',
            'sort' => 3
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Civil',
            ],
            [
                'abbreviation' => 'C'
            ]
        )->update([
            'abbreviation' => 'C',
            'sort' => 4
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Site',
            ],
            [
                'abbreviation' => 'SITE'
            ]
        )->update([
            'abbreviation' => 'SITE',
            'sort' => 5
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Seismic',
            ],
            [
                'abbreviation' => 'SEIS'
            ]
        )->update([
            'abbreviation' => 'SEIS',
            'sort' => 6
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Environmental',
            ],
            [
                'abbreviation' => 'ENV'
            ]
        )->update([
            'abbreviation' => 'ENV',
            'sort' => 7
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Geotechnical',
            ],
            [
                'abbreviation' => 'GEO'
            ]
        )->update([
            'abbreviation' => 'GEO',
            'sort' => 8
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Structural',
            ],
            [
                'abbreviation' => 'STR'
            ]
        )->update([
            'abbreviation' => 'STR',
            'sort' => 9
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Transportation',
            ],
            [
                'abbreviation' => 'TR'
            ]
        )->update([
            'abbreviation' => 'TR',
            'sort' => 10
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Mechanical',
            ],
            [
                'abbreviation' => 'M'
            ]
        )->update([
            'abbreviation' => 'M',
            'sort' => 11
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Plumbing',
            ],
            [
                'abbreviation' => 'P'
            ]
        )->update([
            'abbreviation' => 'P',
            'sort' => 12
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Electrical',
            ],
            [
                'abbreviation' => 'E'
            ]
        )->update([
            'abbreviation' => 'E',
            'sort' => 13
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Information Technology',
            ],
            [
                'abbreviation' => 'IT'
            ]
        )->update([
            'abbreviation' => 'IT',
            'sort' => 14
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Controls',
            ],
            [
                'abbreviation' => 'CONT'
            ]
        )->update([
            'abbreviation' => 'CONT',
            'sort' => 15
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Fire Protection',
            ],
            [
                'abbreviation' => 'FP'
            ]
        )->update([
            'abbreviation' => 'FP',
            'sort' => 16
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Commissioning',
            ],
            [
                'abbreviation' => 'CX'
            ]
        )->update([
            'abbreviation' => 'CX',
            'sort' => 17
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'LEED',
            ],
            [
                'abbreviation' => 'LEED'
            ]
        )->update([
            'abbreviation' => 'LEED',
            'sort' => 18
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Acoustic',
            ],
            [
                'abbreviation' => 'ACOU'
            ]
        )->update([
            'abbreviation' => 'ACOU',
            'sort' => 19
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Environmental, Health & Safety',
            ],
            [
                'abbreviation' => 'EHS'
            ]
        )->update([
            'abbreviation' => 'EHS',
            'sort' => 20
        ]);

        Discipline::firstOrCreate(
            [
                'label' => 'Construction Management',
            ],
            [
                'abbreviation' => 'CM'
            ]
        )->update([
            'abbreviation' => 'CM',
            'sort' => 21
        ]);
    }
}
