<?php

namespace App;

use App\Enum\DocumentationResponseStatus;
use App\Events\DocumentationResponded;

class DocumentationResponse extends BaseFileableModel
{
    protected $guarded = [];

    protected $appends = ['approvesDocument'];

    protected $casts = [
        'created_at' => 'date'
    ];

    protected $dispatchesEvents = [
        'created' => DocumentationResponded::class,
    ];

    public function getApprovesDocumentAttribute()
    {
        return DocumentationResponseStatus::collection()->firstWhere('label', $this->status)['approves'];
    }

    public function submission()
    {
        return $this->belongsTo(DocumentationSubmission::class, 'documentation_submission_id');
    }

    public function sentTo()
    {
        // Historical record of active guest at time of response

        return $this->belongsTo(User::class, 'to_user_id')->withTrashed();
    }

    public function sentFrom()
    {
        // Historical record of active lead at time of submission

        return $this->belongsTo(User::class, 'from_user_id')->withTrashed();
    }

    public function isAssociatedWithUser(User $user): bool
    {
        return $this->submission->documentation->assigned_user_id === $user-> id || $this->submission->documentation->lead_user_id === $user->id;
    }

    public function isAssociatedWithOrganization(Organization $organization): bool
    {
        return $this->submission->documentation->entity->projectDiscipline->project->organization->id === $organization->id;
    }
}
