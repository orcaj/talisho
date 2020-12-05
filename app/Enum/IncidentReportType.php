<?php


namespace App\Enum;


class IncidentReportType extends Enum
{
    const INJURY = [
        'label' => 'Injury',
        'value' => 'injury'
    ];

    const ILLNESS = [
        'label' => 'Illness',
        'value' => 'illness'
    ];

    const NEAR_MISS = [
        'label' => 'Near Miss',
        'value' => 'near-miss'
    ];
}
