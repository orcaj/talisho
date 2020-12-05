<?php

namespace App\Events;

use App\Rfi;
use Illuminate\Queue\SerializesModels;

class RfiCreated
{
    use SerializesModels;

    public $rfi;

    public function __construct(Rfi $rfi)
    {
        $this->rfi = $rfi;
    }
}
