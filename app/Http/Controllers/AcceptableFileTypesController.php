<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcceptableFileTypesController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(config('filesystems.acceptable-file-types'));
    }
}
