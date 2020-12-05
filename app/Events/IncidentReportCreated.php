<?php

namespace App\Events;

use App\IncidentReport;
use Illuminate\Queue\SerializesModels;

class IncidentReportCreated
{
    use SerializesModels;

    public $incidentReport;

    public function __construct(IncidentReport $incidentReport)
    {
        $this->incidentReport = $incidentReport;
    }
}
