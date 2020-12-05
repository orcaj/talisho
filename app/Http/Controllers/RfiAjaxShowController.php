<?php

namespace App\Http\Controllers;

use App\Rfi;
use Illuminate\Http\Request;

class RfiAjaxShowController extends Controller
{
    public function __invoke(Request $request, Rfi $rfi)
    {
        abort_unless($rfi->isVisibleForUser($request->user()), 403);

        return $rfi->load(
            'projectDiscipline.discipline',
            'projectDiscipline.lead.organization',
            'requestedBy.organization',
            'files',
            'response.files',
            'response.respondedBy.organization'
        );
    }
}
