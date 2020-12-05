<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherDocument extends Model
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

    public function projectDiscipline()
    {
        return $this->belongsTo(ProjectDiscipline::class);
    }

    public function submittal()
    {
        return $this->belongsTo(Submittal::class);
    }
}
