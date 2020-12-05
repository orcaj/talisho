<?php

namespace App;

use App\Enum\DocumentType;
use App\Enum\MessagingStatus;
use App\Enum\Permission;
use App\Events\DocumentationCreated;
use App\Events\DocumentationReassigned;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

class Documentation extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $appends = ['isOutstanding', 'isUnderReview', 'isApproved'];

    protected $dispatchesEvents = [
        'created' => DocumentationCreated::class,
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    public function entity()
    {
        return $this->morphTo('entity');
    }

    public function lead()
    {
        return $this->belongsTo(User::class, 'lead_user_id');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function submissions()
    {
        return $this->hasMany(DocumentationSubmission::class, 'documentation_id');
    }

    public function responses()
    {
        return $this->hasManyThrough(DocumentationResponse::class, DocumentationSubmission::class);
    }

    public function notTrashed()
    {
        return ! $this->trashed();
    }

    public function isVisibleForGuest($user)
    {
        return $this->assigned->id === $user->id;
    }

    public function isVisibleForLead($user)
    {
        return $this->lead->id === $user->id;
    }

    public function isVisibleForUser($user)
    {
        return ($user->can(Permission::VIEW_ALL_DOCUMENTATION) && $this->entity->projectDiscipline->project->organization->id === $user->organization->id)
            || $this->isVisibleForGuest($user) || $this->isVisibleForLead($user);
    }

    public function isSubmittalWithAssociatedDocuments()
    {
        return $this->entity_type === Submittal::class && $this->entity->associatedDocuments()->exists();
    }

    public function reassignUser($user, $withEvent = true)
    {
        $this->update([
            'assigned_user_id' => $user->id
        ]);

        if ($withEvent) {
            event(new DocumentationReassigned($this));
        }

        return $this;
    }

    public function changeLead($userId)
    {
        return $this->update([
            'lead_user_id' => $userId
        ]);
    }

    public function markAsApprovedByUpload()
    {
        return $this->update([
            'due_date' => null,
            'status' => MessagingStatus::APPROVED,
            'approved_by_upload' => true
        ]);
    }

    public function getHasMessagesAttribute()
    {
        return $this->submissions()->exists();
    }

    public function getIsOutstandingAttribute()
    {
        return $this->status === MessagingStatus::OUTSTANDING;
    }

    public function getBallInCourtAttribute()
    {
        if ($this->isUnderReview) {
            return $this->lead->name;
        }

        if ($this->isOutstanding) {
            return $this->assigned->name;
        }

        return null;
    }

    public function getIsUnderReviewAttribute()
    {
        // if it has any submission without a response

        return $this->status === MessagingStatus::UNDER_REVIEW;
    }

    public function getIsLateAttribute()
    {
        return optional($this->due_date)->isPast() && ! optional($this->due_date)->isToday();
    }

    public function getDaysLateAttribute()
    {
        return $this->isLate ? $this->due_date->diffInDays(today()) : null;
    }

    public function getIsDueInMoreThanSevenDaysAttribute()
    {
        return optional($this->due_date)->greaterThan(today()->addWeek()) ?? false;
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === MessagingStatus::APPROVED;
    }

    public function getMessagingStatusAttribute()
    {
        return $this->entity->projectDiscipline->isLeadBy(auth()->user()) || auth()->user()->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES)
            ? $this->messagingStatusForProjectManagersAndLeads()
            : $this->messagingStatusForGuests();
    }

    public function messagingStatusForGuests()
    {
        if ($this->isLate && $this->isOutstanding) {
            return MessagingStatus::LATE;
        }

        return $this->consolidatedStatuses();
    }

    public function messagingStatusForProjectManagersAndLeads()
    {
        if ($this->isLate && $this->isUnderReview) {
            return MessagingStatus::LATE;
        }

        return $this->consolidatedStatuses();
    }

    protected function consolidatedStatuses()
    {
        if ($this->isOutstanding) {
            return MessagingStatus::OUTSTANDING;
        }

        if ($this->isUnderReview) {
            return MessagingStatus::UNDER_REVIEW;
        }

        return MessagingStatus::APPROVED;
    }

    public function getBaseFilePathAttribute()
    {
        // replace space and slashes with hyphen
        $type = $this->entity_type === Submittal::class ? DocumentType::SUBMITTAL : preg_replace("/\s+|[\/]/", '-' ,$this->entity->document_type);

        $customPrefix = $this->entity->specification_type === CustomSpecification::class ? 'custom/' : '';

        // Nest associated documents with their affiliated submittal if it exists
        $restOfFilePath = ! is_null($this->entity->submittal_id) ? $customPrefix . $this->entity->submittal->specification->id . '/' . $this->entity->specification->id : $customPrefix . $this->entity->specification->id;

        return "/project/{$this->entity->projectDiscipline->project->id}/discipline/{$this->entity->projectDiscipline->discipline->id}/Documentation/{$type}/{$restOfFilePath}";
    }
}
