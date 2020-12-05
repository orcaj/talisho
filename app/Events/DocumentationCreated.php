<?php

namespace App\Events;

use App\Documentation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class DocumentationCreated
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
