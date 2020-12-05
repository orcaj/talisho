<?php

namespace App\Events;

use App\ConstructionDirective;
use Illuminate\Queue\SerializesModels;

class ConstructionDirectiveCreated
{
    use SerializesModels;

    public $constructionDirective;

    public function __construct(ConstructionDirective $constructionDirective)
    {
        $this->constructionDirective = $constructionDirective;
    }
}
