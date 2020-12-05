<?php

namespace App\Http\Controllers;

use App\Project;
use App\QrCode;
use Illuminate\Http\Request;

class QrCodeInternalController extends Controller
{
    public function __invoke(Request $request, QrCode $qrcode, Project $project)
    {
        return redirect($qrcode->resolve($project));
    }
}
