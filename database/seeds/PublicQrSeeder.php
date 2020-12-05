<?php

use App\QrCode;
use App\Enum\QrCodeType;
use Illuminate\Database\Seeder;

class PublicQrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QrCode::firstOrCreate([
            'slug' => 'nys-posting-requirements',
            'description' => 'New York State Posting Requirements',
            'link' => 'https://labor.ny.gov/workerprotection/laborstandards/employer/posters.shtm',
            'type' => QrCodeType::PUBLIC
        ]);

        QrCode::firstOrCreate([
            'slug' => 'personal-protective-equipment',
            'description' => 'Personal Protective Equipment',
            'link' => 'https://osha.gov/SLTC/personalprotectiveequipment',
            'type' => QrCodeType::PUBLIC
        ]);

        QrCode::firstOrCreate([
            'slug' => 'nys-dosh',
            'description' => 'New York State Division of Safety and Health',
            'link' => 'https://labor.ny.gov/workerprotection/safetyhealth/DOSH_INDEX.shtm',
            'type' => QrCodeType::PUBLIC
        ]);

        QrCode::firstOrCreate([
            'slug' => 'osha-videos',
            'description' => 'OSHA Videos',
            'link' => 'https://osha.gov/video/',
            'type' => QrCodeType::PUBLIC
        ]);

        QrCode::firstOrCreate([
            'slug' => 'revsafety',
            'description' => 'RevSafety YouTube',
            'link' => 'https://www.youtube.com/channel/UCA3Q87RqvllNzBREfLTQztg/videos',
            'type' => QrCodeType::PUBLIC
        ]);

        QrCode::firstOrCreate([
            'slug' => 'taliho',
            'description' => 'Taliho',
            'link' => 'https://www.taliho.com',
            'type' => QrCodeType::PUBLIC
        ]);

        QrCode::firstOrCreate([
            'slug' => 'covid',
            'description' => 'Covid',
            'link' => 'https://www.cdc.gov/coronavirus/2019-ncov/index.html',
            'type' => QrCodeType::PUBLIC
        ]);
    }
}
