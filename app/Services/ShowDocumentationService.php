<?php


namespace App\Services;

use App\Enum\Permission;
use App\Traits\MapsStatuses;
use Illuminate\Support\Collection;

class ShowDocumentationService
{
    use MapsStatuses;

    public function getProjectDisciplinesForUser($project, $user)
    {
        $preloadedDisciplines = $project
            ->disciplines;

        return $user->can(Permission::VIEW_ALL_DOCUMENTATION)
            ? $preloadedDisciplines
            : $preloadedDisciplines->filter->isVisibleFor($user);
    }

    public function getSubmittalsForDisciplines($disciplines)
    {
        return $this->loadDisciplineLeadsAndTeams($disciplines)
            ->loadMissing('submittals.specification', 'submittals.associatedDocuments')
            ->pluck('submittals')
            ->flatten()
            ->sortBy('specification.code')
            ->values();
    }

    public function getOtherDocumentsByTypeForDisciplines($disciplines, $type): Collection
    {
        return $this->loadDisciplineLeadsAndTeams($disciplines)
            ->loadMissing('otherDocuments.specification')
            ->pluck('otherDocuments')
            ->flatten()
            ->where('document_type', $type)
            ->sortBy('specification.code')
            ->values();
    }

    public function mapDocumentDataForDisplay($documents)
    {
        // TODO Clean up references to deleted docs here and in
        //  resources/js/Pages/Organization/Project/Documentation/Show.vue

        // For now, we are just not loading any other documents or submittals if they do not have documentation (deleted)

        return $documents->map(function ($doc) {
            $documentation = $doc->documentation;
            return [
                'id' => $documentation->id,
                'csi_id' => $doc->specification->code,
                'name' => $doc->specification->name,
                'status' => $this->documentMessagingStatus($documentation, request()->user()),
                'messaging_status' => $documentation->messagingStatus,
                'assigned' => $documentation->assigned->name,
                'assigned_user_id' => $documentation->assigned->id,
                'assigned_date' => $documentation->created_at->format('m/d/y'),
                'approved_date' => $documentation->isApproved
                    ? $documentation->submissions->first(function($submission) {
                        return $submission->response && $submission->response->approvesDocument;
                })->response->created_at->format('m/d/y')
                    : null,
                'ball_in_court' => $documentation->ballInCourt,
                'due_date' => optional($doc->documentation->due_date)->format('m/d/y'),
                'days_late' => $documentation->daysLate,
                'project_discipline_id' => $doc->project_discipline_id,
                'documentation_id' => $doc->documentation->id,
                'has_messages' => $documentation->hasMessages,
                'associated_documents' => optional($doc)->associatedDocuments ? $this->mapDocumentDataForDisplay($doc->associatedDocuments) : null,
                'is_deleted' => $documentation->trashed(),
                'approved_by_upload' => $documentation->approved_by_upload
            ];
        })->values();
    }

    protected function loadDisciplineLeadsAndTeams($disciplines)
    {
        return $disciplines->load('discipline', 'lead', 'team');
    }
}
