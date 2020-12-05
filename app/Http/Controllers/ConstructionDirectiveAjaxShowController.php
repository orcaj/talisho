<?php

namespace App\Http\Controllers;

use App\ConstructionDirective;
use Illuminate\Http\Request;

class ConstructionDirectiveAjaxShowController extends Controller
{

    public function __invoke(Request $request, ConstructionDirective $constructionDirective)
    {
        abort_unless($constructionDirective->isVisibleForUser($request->user()), 403);

        return $constructionDirective->load(
            'projectDiscipline.discipline',
            'projectDiscipline.lead.organization',
            'files',
            'guests.organization',
            'sentBy.organization'
        );
    }
}
