<?php

namespace App\Console\Commands;

use App\QrCode;
use App\Enum\QrCodeType;
use Illuminate\Console\Command;

class GeneratePublicQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:public:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates all of the public QR codes that are in the database.';

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
        $qrCodes = QrCode::where('type', QrCodeType::PUBLIC)->get();

        $bar = $this->output->createProgressBar($qrCodes->count());

        $qrCodes->each(function($qr) use ($bar) {
            $qr->generate();
            $bar->advance();
        });

        $bar->finish();

        $this->info("\n Generation Complete!");
    }
}
