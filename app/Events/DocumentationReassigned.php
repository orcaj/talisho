<?php

namespace App\Events;

use App\Documentation;
use Illuminate\Queue\SerializesModels;

class DocumentationReassigned
{
    use SerializesModels;

    public $documentation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Documentation $documentation)
    {
        $this->documentation = $documentation;
    }

}
