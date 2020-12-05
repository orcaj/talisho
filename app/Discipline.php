<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $fillable = [
        'label',
        'sort',
        'abbreviation'
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_disciplines');
    }
}
