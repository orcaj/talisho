<?php

namespace App\Listeners;

use App\QrCode;
use App\QrCSI;
use App\Enum\QrCodeType;
use App\Events\ProjectCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateProjectQrCodes implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  ProjectCreated  $event
     * @return void
     */
    public function handle(ProjectCreated $event)
    {
        // Generate all of the internal QR codes
        $qrCodes = QrCode::where('type', QrCodeType::INTERNAL)->get();
        $qrCodes->each(function($qr) use ($event) {
            $qr->generate($event->project);
        });

        // Generate all of the CSI QR codes
        $csiQrCodes = QrCSI::get();
        $csiQrCodes->each(function($qr) use ($event) {
            $qr->generate($event->project);
        });
    }
}
