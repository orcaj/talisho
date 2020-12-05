<?php

namespace App;


use App\Enum\IncidentReportType;
use App\Enum\Permission;
use App\Enum\ProjectLevelStatuses;
use App\Events\IncidentReportCreated;

class IncidentReport extends BaseFileableModel
{
    protected $guarded = [];

    protected $appends = [
        'isInjuryReport',
        'isIllnessReport',
        'isNearMissReport',
        'incidentDateWithDayOfWeek',
        'status'
    ];

    protected $casts = [
        'incident_date' => 'date',
        'information' => 'array'
    ];

    protected $dispatchesEvents = [
        'created' => IncidentReportCreated::class
    ];

    public function projectDiscipline()
    {
        return $this->belongsTo(ProjectDiscipline::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function reassignUser($user)
    {
        return $this->update([
            'assigned_to_user_id' => $user->id
        ]);
    }

    public function belongsToProjectDiscipline(ProjectDiscipline $projectDiscipline)
    {
        return $this->projectDiscipline->id === $projectDiscipline->id;
    }

    public function isVisibleForUser($user)
    {
        return ($user->can(Permission::VIEW_ALL_LIABILITY) && $this->isAssociatedWithOrganization($user->organization))
            || $this->isAssociatedWithUser($user);
    }

    public function isAssociatedWithUser($user): bool
    {
        return $this->projectDiscipline->lead->id === $user->id || $this->assignedTo->id === $user->id;
    }

    public function isAssociatedWithOrganization($organization): bool
    {
        return $this->projectDiscipline->project->organization->id === $organization->id;
    }

    public function getIsInjuryReportAttribute()
    {
        return $this->information['type'] === IncidentReportType::INJURY['value'];
    }

    public function getIsIllnessReportAttribute()
    {
        return $this->information['type'] === IncidentReportType::ILLNESS['value'];
    }

    public function getIsNearMissReportAttribute()
    {
        return $this->information['type'] === IncidentReportType::NEAR_MISS['value'];
    }

    public function getIncidentDateWithDayOfWeekAttribute()
    {
        return $this->incident_date->format('l m/d/Y');
    }

    public function getStatusAttribute()
    {
        if (!$this->projectDiscipline->isLeadBy(request()->user())
            && !request()->user()->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES)
        ) {
            return ProjectLevelStatuses::NO_ACTION;
        }

        return $this->is_acknowledged ? ProjectLevelStatuses::GOOD_STANDING : ProjectLevelStatuses::ISSUE;
    }
}
