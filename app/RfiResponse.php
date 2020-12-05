<?php

namespace App;


use App\Events\RfiResponseCreated;

class RfiResponse extends BaseFileableModel
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date'
    ];

    public $dispatchesEvents = [
        'created' => RfiResponseCreated::class
    ];

    public function rfi()
    {
        return $this->belongsTo(Rfi::class);
    }

    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'from_lead_user_id');
    }

    public function isAssociatedWithUser(User $user): bool
    {
        return $this->rfi->isAssociatedWithUser($user);
    }

    public function isAssociatedWithOrganization(Organization $organization): bool
    {
        return $this->rfi->isAssociatedWithOrganization($organization);
    }
}
