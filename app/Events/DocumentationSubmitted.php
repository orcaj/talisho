<?php

namespace App\Events;

use App\DocumentationSubmission;
use Illuminate\Queue\SerializesModels;

class DocumentationSubmitted
{
    use SerializesModels;

    public $submission;

    public function __construct(DocumentationSubmission $documentationSubmission)
    {
        $this->submission = $documentationSubmission;
    }
}
