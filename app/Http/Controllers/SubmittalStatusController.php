<?php

namespace App\Http\Controllers;

use App\Enum\DocumentationResponseStatus;
use App\Submittal;
use Illuminate\Http\Request;

class SubmittalStatusController extends Controller
{
    public function __invoke(Request $request)
    {
        return DocumentationResponseStatus::collection()->filter(function ($status) {
            return $status['group'] === Submittal::class;
        })
            ->values();
    }
}
