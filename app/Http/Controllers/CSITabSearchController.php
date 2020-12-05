<?php

namespace App\Http\Controllers;

use App\CSI;
use Illuminate\Http\Request;

class CSITabSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        return CSI::with('division')
            ->searchByNameOrCode($request->input('search'))
            ->take(25)
            ->get();
    }
}
