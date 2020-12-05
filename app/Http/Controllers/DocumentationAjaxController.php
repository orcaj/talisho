<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Events\DocumentationReassigned;
use App\Http\Requests\UpdateDocumentationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DocumentationAjaxController extends Controller
{
    public function show(Request $request, Documentation $documentation)
    {
        abort_unless($documentation->isVisibleForUser($request->user()), 403);

        return $documentation->append(['messagingStatus'])->load(
            'entity.specification',
            'entity.projectDiscipline.discipline',
            'assigned.organization',
            'lead.organization',
            'submissions.sentTo.organization',
            'submissions.sentFrom.organization',
            'submissions.files',
            'submissions.response.sentTo',
            'submissions.response.sentFrom.organization',
            'submissions.response.files'
        );
    }

    public function destroy(Request $request, Documentation $documentation)
    {
        abort_unless($documentation->isVisibleForLead($request->user()), 403);

        if ($documentation->isSubmittalWithAssociatedDocuments()) {
            $documentation->entity->associatedDocuments()->with('documentation')->get()->pluck('documentation')->each->delete();
        }

        $documentation->delete();

        return Redirect::back()->with('success', 'Document has been deleted');
    }
}
