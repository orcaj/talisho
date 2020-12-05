<?php

namespace App\Http\Controllers;

use App\Enum\IncidentReportType;
use Illuminate\Http\Request;

class IncidentReportTypesController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(IncidentReportType::values());
    }
}
