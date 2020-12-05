<?php

namespace App;

use App\Traits\TalihoNotifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;

class TalihoNotification extends DatabaseNotification
{
    use TalihoNotifiable, SoftDeletes;

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime:m/d/Y'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
