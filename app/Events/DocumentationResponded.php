<?php

namespace App\Events;

use App\DocumentationResponse;
use Illuminate\Queue\SerializesModels;

class DocumentationResponded
{
    use SerializesModels;

    public $documentationResponse;

    public function __construct(DocumentationResponse $documentationResponse)
    {
        $this->documentationResponse = $documentationResponse;
    }
}
