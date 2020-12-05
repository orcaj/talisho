<?php

namespace App\Events;

use App\Project;
use Illuminate\Queue\SerializesModels;

class ProjectCreated
{
    use SerializesModels;

    public $project;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
}
