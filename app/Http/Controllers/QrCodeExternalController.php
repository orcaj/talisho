<?php

namespace App\Http\Controllers;

use App\QrCode;
use Illuminate\Http\Request;

class QrCodeExternalController extends Controller
{
    public function __invoke(Request $request, QrCode $qrcode)
    {
        return redirect($qrcode->link);
    }
}
