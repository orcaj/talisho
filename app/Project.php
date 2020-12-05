<?php

namespace App;

use App\Events\ProjectCreated;
use App\Enum\ProjectLevelStatuses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    protected $guarded = [];

    protected $appends = [
        'path',
    ];

    protected $dispatchesEvents = [
        'created' => ProjectCreated::class,
    ];

    public function getPathAttribute()
    {
        return "/projects/{$this->id}";
    }

    public function disciplines()
    {
        return $this->hasMany(ProjectDiscipline::class);
    }

    public function designDocuments()
    {
        return $this->hasMany(DesignDocument::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function otherDocuments()
    {
        return $this->hasManyThrough(OtherDocument::class, ProjectDiscipline::class);
    }

    public function submittals()
    {
        return $this->hasManyThrough(Submittal::class, ProjectDiscipline::class);
    }

    public function getSummaryDataAttribute()
    {
        return $this->disciplines->each->append('summaryData')->pluck('summaryData')->reduce(function ($prev, $next) {
            return [
                'approved_documents' => $prev['approved_documents'] + $next['approved_documents'],
                'total_documents' => $prev['total_documents'] + $next['total_documents'],
                'documents_under_review' => $prev['documents_under_review'] + $next['documents_under_review'],
                'documents_late' => $prev['documents_late'] + $next['documents_late'],
                'documentation_needs_action' => $prev['documentation_needs_action'] ? $prev['documentation_needs_action'] : $next['documentation_needs_action'],
                'rfis_under_review' => $prev['rfis_under_review'] + $next['rfis_under_review'],
                'rfi_needs_action' => $prev['rfi_needs_action'] ? $prev['rfi_needs_action'] : $next['rfi_needs_action'],
                'rfis_late' => $prev['rfis_late'] + $next['rfis_late'],
                'daily_logs_missing' => $prev['daily_logs_missing'] + $next['daily_logs_missing'],
                'incident_reports' => $prev['incident_reports'] + $next['incident_reports'],
                'unacknowledged_incident_reports' => $prev['unacknowledged_incident_reports'] + $next['unacknowledged_incident_reports'],
                'documentation_status' =>  ($prev['documentation_status'] + $next['documentation_status']) >=1 ? 1:0,
                'communication_status' => ($prev['communication_status'] + $next['communication_status']) >=1 ? 1:0,
                'liability_status' => ($prev['liability_status'] + $next['liability_status']) >=1 ? 1:0,
            ];
        },
        [
            'approved_documents' => 0,
            'total_documents' => 0,
            'documents_under_review' => 0,
            'documents_late' => 0,
            'documentation_needs_action' => false,
            'rfis_under_review' => 0,
            'rfi_needs_action' => false,
            'rfis_late' => 0,
            'daily_logs_missing' => 0,
            'incident_reports' => 0,
            'unacknowledged_incident_reports' => 0,
            'documentation_status' => 0,
            'communication_status' => 0,
            'liability_status' => 0,
        ]);
    }

    public function deriveStatus($statuses)
    {
        return $statuses->sortBy('priority')->first() ?? ProjectLevelStatuses::NO_ACTION;
    }

    public function tabStatuses($user)
    {
        return [
            'documentation' => $this->statusForDisciplineAttribute('documentationStatus', $user),
            'communication' => $this->statusForDisciplineAttribute('communicationStatus', $user),
            'liability' => $this->statusForDisciplineAttribute('liabilityStatus')
        ];
    }

    public function statusForDisciplineAttribute($property, $user = null)
    {
        return $this->disciplines->mapToGroups(function ($disc) use ($property, $user) {
            return [$disc->id => $disc->{$property}($user)];
        });
    }

    public function getDesignDocumentBaseFilePathAttribute()
    {
        return "project/{$this->id}/Design-Documents/";
    }

    public function scopeActive($query, $bool = 'and')
    {
        return $query->whereNull('closed_at', $bool);
    }

    public function scopeClosed($query, $bool = 'and')
    {
        return $query->whereNotNull('closed_at', $bool);
    }

    public function scopeWhereUserLeadsDiscipline($query, User $user)
    {
        return $query->with(['disciplines' => function ($q) use ($user) {
            $q->whereUserId($user->id);
        }])->whereHas('disciplines', function ($q) use ($user) {
            $q->whereUserId($user->id);
        });
    }

    public function scopeWhereUserBelongsToProjectTeam($query, User $user)
    {
        return $query->with(['disciplines' => function ($q) use ($user) {
            $q->whereIn(
                'id',
                DB::table('project_discipline_guests')->select('project_discipline_id')->whereUserId($user->id)
            );
        }])->whereHas('disciplines.team', function ($q) use ($user) {
            $q->whereUserId($user->id);
        });
    }
}
