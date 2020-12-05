<?php

namespace App;


class DailyLogComment extends BaseFileableModel
{
    protected $guarded = [];

    public function dailyLog()
    {
        return $this->belongsTo(DailyLog::class);
    }

    public function commentedBy()
    {
        return $this->belongsTo(User::class, 'comment_by_user_id');
    }

    public function isAssociatedWithUser($user): bool
    {
        return $this->dailyLog->projectDiscipline->lead->id === $user->id || $this->dailyLog->projectDiscipline->userBelongsToTeam($user);
    }

    public function isAssociatedWithOrganization($organization): bool
    {
        return $this->dailyLog->projectDiscipline->project->organization->id === $organization->id;
    }
}
