<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Events\DocumentationReassigned;
use App\Http\Requests\UpdateMultipleDocumentationAssignmentsRequest;
use Illuminate\Support\Facades\Redirect;

class UpdateDocumentationAssignmentController extends Controller
{
    public function __invoke(UpdateMultipleDocumentationAssignmentsRequest $request)
    {
        $allDocs = Documentation::findMany($request->validated()['documentation_ids']);

        $allDocs->each(function (Documentation $documentation) use ($request) {
            $documentation->update([
               'assigned_user_id' => $request->validated()['assigned_user_id']
            ]);

            if ($documentation->isSubmittalWithAssociatedDocuments()) {
                $documentation->entity->associatedDocuments()->with('documentation')->get()->pluck('documentation')->each->update([
                    'assigned_user_id' => $request->validated()['assigned_user_id']
                ]);
            }

            event(new DocumentationReassigned($documentation));
        });

        return Redirect::back()->with('success', 'Documents have been re-assigned');
    }
}
