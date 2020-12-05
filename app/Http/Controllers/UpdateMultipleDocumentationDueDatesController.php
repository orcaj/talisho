<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Http\Requests\UpdateMultipleDocumentationDueDatesRequest;
use Illuminate\Support\Facades\Redirect;

class UpdateMultipleDocumentationDueDatesController extends Controller
{
    public function __invoke(UpdateMultipleDocumentationDueDatesRequest $request)
    {
        Documentation::findMany($request->validated()['documentation_ids'])->each(function (Documentation $documentation) use ($request) {
            $documentation->update([
                'due_date' => $request->validated()['due_date']
            ]);
        });


        return Redirect::back()->with('success', 'Due dates have been updated.');
    }
}
