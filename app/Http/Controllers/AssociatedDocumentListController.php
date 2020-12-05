<?php

namespace App\Http\Controllers;

use App\CSIQuickList;
use Illuminate\Http\Request;

class AssociatedDocumentListController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(
          CSIQuickList::associatedDocuments()->with('csi')->get()->pluck('csi')
        );
    }
}
