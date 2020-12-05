<?php

namespace App\Http\Controllers;

use App\CSI;
use App\CSIDivision;
use Illuminate\Http\Request;

class CSISubmittalsSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $filter = $request->input('filterDivisionId');
        return CSI::with('division')
            ->searchByNameOrCode($request->input('search'))
            ->take(25)
            ->unless($filter,  function ($query) {
                return $query->whereIn('csi_division_id', CSIDivision::submittals()->get()->modelKeys());
            })
            ->when($filter, function ($query) use ($filter) {
                return $query->where('csi_division_id', $filter);
            })
            ->get();
    }
}
