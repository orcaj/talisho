<?php

namespace App;


use App\Enum\MessagingStatus;
use App\Enum\Permission;
use App\Events\RfiCreated;
use Carbon\Carbon;

class Rfi extends BaseFileableModel
{
    protected $guarded = [];

    protected $appends = ['isUnderReview', 'isFinalized'];

    protected $casts = [
        'due_date' => 'date',
        'created_at' => 'date'
    ];

    protected $dispatchesEvents = [
        'created' => RfiCreated::class
    ];

    public function projectDiscipline()
    {
        return $this->belongsTo(ProjectDiscipline::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'guest_user_id');
    }

    public function response()
    {
        return $this->hasOne(RfiResponse::class);
    }

    public function hasResponse()
    {
        return !is_null($this->response);
    }

    public function reassignUser($user)
    {
        return $this->update([
            'guest_user_id' => $user->id
        ]);
    }

    public function getIsUnderReviewAttribute()
    {
        return $this->response()->doesntExist();
    }

    public function getIsFinalizedAttribute()
    {
        return $this->response()->exists();
    }

    public function getBallInCourtAttribute()
    {
        return $this->isUnderReview ? $this->projectDiscipline->lead->name : null;
    }

    public function getDaysLateAttribute()
    {
        return $this->isLate ? $this->due_date->diffInDays(today()) : null;
    }

    public function belongsToProjectDiscipline(ProjectDiscipline $projectDiscipline)
    {
        return $this->projectDiscipline->id === $projectDiscipline->id;
    }

    public function isVisibleForUser($user)
    {
        return ($user->can(Permission::VIEW_ALL_COMMUNICATION) && $this->isAssociatedWithOrganization($user->organization))
            || $this->isAssociatedWithUser($user);
    }

    public function isAssociatedWithUser(User $user): bool
    {
        return $this->guest_user_id === $user->id || $this->projectDiscipline->lead->id === $user->id;
    }

    public function isAssociatedWithOrganization(Organization $organization): bool
    {
        return $this->projectDiscipline->project->organization->id === $organization->id;
    }

    public function getIsLateAttribute()
    {
        return $this->due_date->isPast() && !$this->due_date->isToday() && !$this->isFinalized;
    }

    public function getStatusAttribute()
    {
        if ($this->isLate && (
                $this->projectDiscipline->isLeadBy(request()->user())
                || request()->user()->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES))
        ) {
            return MessagingStatus::LATE;
        }

        if ($this->isUnderReview) {
            return MessagingStatus::UNDER_REVIEW;
        }

        return MessagingStatus::APPROVED;
    }
}
