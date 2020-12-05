<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submittal extends Model
{
    protected $guarded = [];

    public function specification()
    {
        return $this->morphTo();
    }

    public function documentation()
    {
        return $this->morphOne(Documentation::class, 'entity');
    }

    public function associatedDocuments()
    {
        return $this->hasMany(OtherDocument::class, 'submittal_id')->whereHas('documentation');
    }

    public function projectDiscipline()
    {
        return $this->belongsTo(ProjectDiscipline::class);
    }
}
