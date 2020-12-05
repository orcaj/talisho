<?php

namespace App;


use App\Events\DailyLogCreated;

class DailyLog extends BaseFileableModel
{
    protected $guarded = [];

    protected $casts = [
        'log_date' => 'date',
        'information' => 'array'
    ];

    protected $dispatchesEvents = [
        'created' => DailyLogCreated::class
    ];

    public function projectDiscipline()
    {
        return $this->belongsTo(ProjectDiscipline::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }

    public function comments()
    {
        return $this->hasMany(DailyLogComment::class);
    }

    public function isAssociatedWithUser($user): bool
    {
        return $this->projectDiscipline->lead->id === $user->id || $this->projectDiscipline->userBelongsToTeam($user);
    }

    public function isAssociatedWithOrganization($organization): bool
    {
        return $this->projectDiscipline->project->organization->id === $organization->id;
    }

    public function getWereDelaysAttribute()
    {
        if (is_null($this->information)) {
            return null;
        }

        if (is_null($this->information['wereConstructionDelays']) && is_null($this->information['wasWeatherDelay'])) {
            return null;
        }

        return $this->information['wereConstructionDelays'] || $this->information['wasWeatherDelay'];
    }

    public function getWereIssuesAttribute()
    {
        if (is_null($this->information)) {
            return null;
        }

        if (is_null($this->information['wereConstructionIssues'])
            && is_null($this->information['wereSafetyReportsToday'])
            && is_null($this->information['wereNearMissesToday'])
            && is_null($this->information['wereInjuriesToday'])
            && is_null($this->information['wereAccidentsToday'])) {
            return null;
        }

        return $this->information['wereConstructionIssues']
            || $this->information['wereSafetyReportsToday']
            || $this->information['wereNearMissesToday']
            || $this->information['wereInjuriesToday']
            || $this->information['wereAccidentsToday'];
    }

    public function getLogDateWithDayOfWeekAttribute()
    {
        return $this->log_date->format('l m/d/Y');
    }
}
