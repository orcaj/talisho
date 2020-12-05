<?php

namespace App;

use App\Events\DocumentationSubmitted;

class DocumentationSubmission extends BaseFileableModel
{
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date'
    ];

    protected $dispatchesEvents = [
        'created' => DocumentationSubmitted::class,
    ];

    public function documentation()
    {
        return $this->belongsTo(Documentation::class);
    }

    public function response()
    {
        return $this->hasOne(DocumentationResponse::class);
    }

    public function sentTo()
    {
        // Historical record of active lead at time of submission

        return $this->belongsTo(User::class, 'to_user_id')->withTrashed();
    }

    public function sentFrom()
    {
        // Historical record of active guest at time of submission

        return $this->belongsTo(User::class, 'from_user_id')->withTrashed();
    }

    public function isAssociatedWithUser(User $user): bool
    {
        return $this->documentation->assigned_user_id === $user->id || $this->documentation->lead_user_id === $user->id;
    }

    public function isAssociatedWithOrganization(Organization $organization): bool
    {
        return $this->documentation->entity->projectDiscipline->project->organization->id === $organization->id;
    }
}
