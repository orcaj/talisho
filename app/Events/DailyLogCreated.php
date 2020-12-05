<?php

namespace App\Events;

use App\DailyLog;
use Illuminate\Queue\SerializesModels;

class DailyLogCreated
{
    use SerializesModels;

    public $dailyLog;

    public function __construct(DailyLog $dailyLog)
    {
        $this->dailyLog = $dailyLog;
    }
}
