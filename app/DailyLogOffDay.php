<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyLogOffDay extends Model
{
    protected $guarded = [];

    protected $casts = [
        'off_date' => 'date',
    ];

    public function projectDiscipline()
    {
        return $this->belongsTo(ProjectDiscipline::class);
    }
}
