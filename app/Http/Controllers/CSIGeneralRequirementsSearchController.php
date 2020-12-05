<?php

namespace App\Http\Controllers;

use App\CSI;
use App\CSIDivision;
use Illuminate\Http\Request;

class CSIGeneralRequirementsSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $search = $request->input('search');
        return CSI::with('division')
            ->searchByNameOrCode($search)
            ->take(25)
            ->whereIn('csi_division_id', CSIDivision::generalRequirements()->get()->modelKeys())
            ->get();
    }
}
