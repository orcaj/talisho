<?php

namespace App\Http\Controllers;

use App\Enum\GroundConditionType;
use Illuminate\Http\Request;

class GroundConditionTypesController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(GroundConditionType::values());
    }
}
