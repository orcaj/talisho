<?php

namespace App\Http\Controllers;

use App\Enum\DocumentationResponseStatus;
use App\OtherDocument;
use Illuminate\Http\Request;

class OtherDocumentStatusController extends Controller
{
    public function __invoke(Request $request)
    {
        return DocumentationResponseStatus::collection()->filter(function ($status) {
            return $status['group'] === OtherDocument::class;
        })
            ->values();
    }
}
