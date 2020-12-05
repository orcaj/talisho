<?php


namespace App\Services;

use App\Enum\DocumentType;
use App\ProjectDiscipline;
use App\Traits\MapsStatuses;

class ProjectDisciplineStatusesService
{
    use MapsStatuses;

    public function mapDisciplinesToSummaryData($disciplines, $user)
    {
        $allDocumentTypes = DocumentType::collection();

        return $disciplines->mapToGroups(function (ProjectDiscipline $disc) use ($allDocumentTypes, $user) {
            return [$disc->id => $allDocumentTypes->map(function ($type) use ($disc, $user) {
                $docs = $disc->documentsByTypeAndUser($type, $user);
                $count = $docs->count();

                return [
                    'title' => $type,
                    'status' => $this->documentationStatusMap($disc, $user)[$type],
                    'count' => $count,
                ];
            })->values()];
        });
    }
}
