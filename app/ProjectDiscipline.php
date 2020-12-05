<?php

namespace App;

use App\Enum\DocumentType;
use App\Enum\MessagingStatus;
use App\Enum\ProjectLevelStatuses;
use App\Traits\MapsStatuses;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Enum\Permission;

class ProjectDiscipline extends Model
{
    use MapsStatuses;

    protected $guarded = [];

    protected $casts = [
        'active_at' => 'date'
    ];

    public $timestamps = false;

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function lead()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isLeadBy(User $user)
    {
        return ($this->user_id === $user->id) ||($user->can(Permission::CREATE_PROJECTS) && $user->organization->id === $this->project->organization['id']);
    }

    public function team()
    {
        return $this->belongsToMany(User::class, 'project_discipline_guests')->withTimestamps();
    }

    public function otherDocuments()
    {
        return $this->hasMany(OtherDocument::class)->whereHas('documentation');
    }

    public function submittals()
    {
        return $this->hasMany(Submittal::class)->whereHas('documentation');
    }

    public function rfis()
    {
        return $this->hasMany(Rfi::class);
    }

    public function constructionDirectives()
    {
        return $this->hasMany(ConstructionDirective::class);
    }

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class);
    }

    public function unacknowledgedIncidentReports()
    {
        return $this->incidentReports()->where('is_acknowledged', 0);
    }

    public function dailyLogs()
    {
        return $this->hasMany(DailyLog::class);
    }

    public function dailyLogOffDays()
    {
        return $this->hasMany(DailyLogOffDay::class);
    }

    public function replaceUser($oldUser, $newUser)
    {
        $this->team()->detach($oldUser);

        if (!$this->team->contains($newUser)) {
            $this->team()->attach($newUser);
        }

        return $this;
    }

    public function isVisibleFor($user)
    {
        return $this->isLeadBy($user) || $this->userBelongsToTeam($user);
    }

    public function userBelongsToTeam($user)
    {
        return $this->team->contains($user);
    }

    public function generateIdentifier($currentCount)
    {
        return $this->discipline->abbreviation . '-' . str_pad($currentCount + 1, 3, '0', STR_PAD_LEFT);
    }

    public function documentsForUser($user)
    {
        $otherDocsByType = $this->otherDocuments->filter(function ($document) use ($user) {
            return $document->documentation->isVisibleForUser($user);
        });

        return $this->submittals->filter(function ($submittal) use ($user) {
            return $submittal->documentation->isVisibleForUser($user);
        })->concat($otherDocsByType)->filter(function ($document) {
            return !$document->documentation->trashed();
        });
    }

    public function documentsByTypeAndUser($type, $user)
    {
        $docs = $this->documentsForUser($user);

        $docsByType = $docs->where('document_type', $type);

        return $type === DocumentType::SUBMITTAL
            ? $docs->filter(function ($documents) {
                return $documents->documentation->entity_type === Submittal::class;
            })
            : $docsByType;
    }

    public function getDateRangeAttribute()
    {
        return CarbonPeriod::create($this->active_at ?? Carbon::now(), Carbon::yesterday());
    }

    public function daysWithoutDailyLog()
    {
        return collect($this->dateRange)->filter(function ($date) {
            return !$this->dailyLogs->contains(function ($log) use ($date) {
                    return $log->log_date->isSameDay($date) && $log->status !== 'MISSING';
                })
                &&
                !$this->dailyLogOffDays->contains(function ($offDay) use ($date) {
                    return $offDay->off_date->isSameDay($date) && $offDay->status !== 'OFF_DAY';
                });
        });
    }

    public function approvedDocuments()
    {
        return $this->allDocuments()->pluck('documentation')->filter->notTrashed()->map->messagingStatus->flatten()->filter(function ($status) {
            return $status === MessagingStatus::APPROVED;
        });
    }

    public function allDocuments()
    {
        return $this->otherDocuments->concat($this->submittals);
    }

    public function getSummaryDataAttribute()
    {
        // TODO Try to refactor to push heavy lifting to DB rather than collections, performance is taking a serious hit
        $allDocuments = $this->allDocuments()->pluck('documentation')->filter->notTrashed();

        $underReviewDocuments = $allDocuments->filter->isUnderReview;
        $underReviewDocumentsWithoutLate = $underReviewDocuments->filter(function ($doc) {
            return !$doc->isLate;
        })->count();
        $lateDocuments = $underReviewDocuments->filter->isLate->count();

        return [
            'approved_documents' => $this->documentation_status ==0 ? 0: $this->approvedDocuments()->count(),
            'total_documents' =>  $this->documentation_status ==0 ? 0:  $allDocuments->count(),
            'documents_under_review' =>  $this->documentation_status ==0 ? 0:  $underReviewDocumentsWithoutLate,
            'documentation_needs_action' =>  $this->documentation_status ==0 ? 0:  $this->documentationHasWarningCondition($allDocuments, auth()->user()),
            'documents_late' =>  $this->documentation_status ==0 ? 0:  $lateDocuments,
            'rfis_under_review' => $this->communication_status ==0 ? 0:  $this->countOfUnderReviewRFIs(),
            'rfi_needs_action' =>$this->communication_status ==0 ? 0:  $this->rfiHasWarningCondition(auth()->user()),
            'rfis_late' => $this->communication_status ==0 ? 0:  $this->countOfLateRFIs(),
            'daily_logs_missing' => $this->team()->doesntExist() || $this->liability_status ==0 ? 0 : $this->daysWithoutDailyLog()->count(),
            'incident_reports' => $this->liability_status ==0 ? 0 : $this->incidentReports()->count(),
            'unacknowledged_incident_reports' => $this->liability_status ==0 ? 0 : $this->unacknowledgedIncidentReports()->count(),
            'documentation_status' => $this->documentation_status,
            'communication_status' => $this->communication_status,
            'liability_status' => $this->liability_status,
            'documentation_status' => $this->documentation_status,
            'communication_status' => $this->communication_status,
            'liability_status' => $this->liability_status,
        ];
    }

    public function countOfLateRFIs()
    {
        return $this->rfis->filter->isLate->count();
    }

    public function countOfUnderReviewRFIs()
    {
        return $this->rfis->filter(function ($rfi) {
            return $rfi->isUnderReview && !$rfi->isLate;
        })->count();
    }

    public function submittalsWithAssociatedDocuments()
    {
        return $this->otherDocuments
            ->where('document_type', DocumentType::SUBMITTAL)
            ->concat($this->submittals);
    }

    public function documentationHasWarningCondition($documentation, $user)
    {
        return $documentation->map(function ($doc) use ($user) {
            return $this->documentMessagingStatus($doc, $user);
        })->contains(ProjectLevelStatuses::WARNING);
    }

    public function rfiHasWarningCondition($user)
    {
        return $this->rfis->map(function ($rfi) use ($user) {
            return $this->rfiStatusMap($rfi, $user);
        })->contains(ProjectLevelStatuses::WARNING);
    }

    public function statuses($user)
    {
        return collect([
                $this->documentationStatus($user),
                $this->communicationStatus($user),
                $this->liabilityStatus(),
            ])
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    // documentation status
    public function documentationStatus($user)
    {
        return collect([
                $this->generalRequirementsStatus($user),
                $this->submittalsStatus($user),
                $this->tabCxLeedStatus($user),
            ])
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    public function generalRequirementsStatus($user)
    {
        if($this->documentation_status ==0){
            return ProjectLevelStatuses::NO_ACTION;
        }

        return $this->otherDocuments
                ->where('document_type', DocumentType::GENERAL_REQUIREMENT)
                ->filter(function ($document) use ($user) {
                    return $document->documentation->isVisibleForUser($user);
                })
                ->pluck('documentation')
                ->map(function ($doc) use ($user) {
                    return $this->documentMessagingStatus($doc, $user);
                })
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    public function submittalsStatus($user)
    {
        if($this->documentation_status ==0){
            return ProjectLevelStatuses::NO_ACTION;
        }

        return $this->submittalsWithAssociatedDocuments()
                ->filter(function ($document) use ($user) {
                    return $document->documentation->isVisibleForUser($user);
                })
                ->pluck('documentation')
                ->map(function ($doc) use ($user) {
                    return $this->documentMessagingStatus($doc, $user);
                })
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    public function tabCXLeedStatus($user)
    {
        if($this->documentation_status ==0){
            return ProjectLevelStatuses::NO_ACTION;
        }

        return $this->otherDocuments
                ->where('document_type', DocumentType::TAB_CX_LEED)
                ->filter(function ($document) use ($user) {
                    return $document->documentation->isVisibleForUser($user);
                })
                ->pluck('documentation')
                ->map(function ($doc) use ($user) {
                    return $this->documentMessagingStatus($doc, $user);
                })
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    // communication status
    // Only RFIs contribute to communication status - construction directives have no status

    public function communicationStatus($user)
    {
        return collect([
                $this->rfiStatus($user),
                $this->constructionDirectiveStatus
            ])
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    public function rfiStatus($user)
    {
        if($this->communication_status ==0){
            return ProjectLevelStatuses::NO_ACTION;
        }

        return $this->rfis
                ->filter(function ($rfi) use ($user) {
                    return $rfi->isVisibleForUser($user);
                })
                ->map(function ($rfi) use ($user) {
                    return $this->rfiStatusMap($rfi, $user);
                })
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    public function getConstructionDirectiveStatusAttribute()
    {
        if($this->communication_status ==0){
            return ProjectLevelStatuses::NO_ACTION;
        }

        return $this->constructionDirectives()->count() === 0
            ? ProjectLevelStatuses::NO_ACTION
            : ProjectLevelStatuses::GOOD_STANDING;
    }

    // liability status

    public function liabilityStatus()
    {
        return collect([
                $this->incidentReportStatus,
                $this->dailyLogStatus
            ])
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    public function getDailyLogStatusAttribute()
    {
        if($this->liability_status ==0){
            return ProjectLevelStatuses::NO_ACTION;
        }

        if ($this->team()->doesntExist()) {
            return ProjectLevelStatuses::NO_ACTION;
        }

        return $this->daysWithoutDailyLog()->isNotEmpty()
            ? ProjectLevelStatuses::ISSUE
            : ProjectLevelStatuses::GOOD_STANDING;
    }

    public function getIncidentReportStatusAttribute()
    {
        if($this->liability_status ==0){
            return ProjectLevelStatuses::NO_ACTION;
        }

        return $this->incidentReports->map->status
                ->sortBy('priority')
                ->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    public function getRfiBasePathAttribute()
    {
        return "project/{$this->project->id}/discipline/{$this->discipline->id}/Communication/RFIs";
    }

    public function getConstructionDirectiveBasePathAttribute()
    {
        return "project/{$this->project->id}/discipline/{$this->discipline->id}/Communication/Construction-Directives";
    }

    public function getIncidentReportBasePathAttribute()
    {
        return "project/{$this->project->id}/discipline/{$this->discipline->id}/Liability/Incident-Reports";
    }

    public function getDailyLogBasePathAttribute()
    {
        return "project/{$this->project->id}/discipline/{$this->discipline->id}/Liability/Daily-Logs";
    }
}
