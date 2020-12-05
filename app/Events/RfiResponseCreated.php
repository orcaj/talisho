<?php

namespace App\Events;

use App\RfiResponse;
use Illuminate\Queue\SerializesModels;

class RfiResponseCreated
{
    use SerializesModels;

    public $rfiResponse;

    public function __construct(RfiResponse $rfiResponse)
    {
        $this->rfiResponse = $rfiResponse;
    }
}
