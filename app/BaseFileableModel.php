<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class BaseFileableModel extends Model
{
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    abstract public function isAssociatedWithUser(User $user): bool;

    abstract public function isAssociatedWithOrganization(Organization $organization): bool;
}
