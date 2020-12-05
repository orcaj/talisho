<?php

namespace App\Console\Commands;

use App\CSI;
use App\QrCSI;
use App\QrCode;
use App\Enum\QrCodeType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedInternalQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:internal:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds the database with internal QR codes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Clear out any data in the qr_csis table
        Schema::disableForeignKeyConstraints();
        DB::table('qr_csis')->truncate();
        Schema::enableForeignKeyConstraints();

        // Seed fresh data into the qr_csis table
        $this->QrCSIs()->each(function ($csiCode) {
            $csi = CSI::where('code', $csiCode['code'])->first();
            if (!$csi || !$csi->exists) {
                $code = $csiCode['code'];
                $this->info("CSI $code not found in database");
                return true;
            }

            $QrCSI = QrCSI::create([
                'csi_id' => $csi->id,
                'name' => $csiCode['name']
            ]);
        });
        $this->info("Seeded qr_csis table");

        // Seed the qr_codes table with CSI routes
        $this->CSIQrCodeData()->each(function ($qrData) {
            $qrcode = QrCode::updateOrCreate([
                'slug' => $qrData['slug']
            ], [
                'link' => $qrData['route'],
                'description' => $qrData['description'],
                'type' => QrCodeType::INTERNAL
            ]);
        });
        $this->info("Seeded qr_codes table with internal code data");
    }

    protected function QrCSIs()
    {

        // TODO these are just associated documents until confirmed
        return collect([
            [
                'code' => '017823.13',
                'name' => 'Operation Manuals',
            ],
            [
                'code' => '017823.16',
                'name' => 'Maintenance Manuals',
            ],
            [
                'code' => '017836',
                'name' => 'Product Warranties',
            ],
            [
                'code' => '017500',
                'name' => 'Starting and Adjusting Reports',
            ],
            [
                'code' => '017900',
                'name' => 'Demonstration and Training',
            ],
            [
                'code' => '017823.19',
                'name' => 'Preventative Maintenance Instructions',
            ],
            [
                'code' => '017819',
                'name' => 'Maintenance Contracts',
            ],
            [
                'code' => '013529',
                'name' => 'Material Safety Data Sheets',
            ],
            [
                'code' => '230593',
                'name' => 'Testing, Adjusting, and Balancing'
            ],
            [
                'code' => '017853',
                'name' => 'Sustainability Documentation'
            ],
            [
                'code' => '017839',
                'name' => 'Record Drawings'
            ]
        ]);
    }

    protected function CSIQrCodeData()
    {
        return collect([
            [
                'route' => 'qr-code.submittal',
                'description' => 'Approved Submittals',
                'slug' => 'approved-submittals'
            ],
            [
                'route' => 'qr-code.commissioning',
                'description' => 'Commissioning Documentation',
                'slug' => 'commissioning-documentation'
            ]
        ]);
    }
}
