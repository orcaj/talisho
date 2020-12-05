<?php


namespace App\Enum;


class GroundConditionType extends Enum
{
    const DRY = [
        'label' => 'Dry/Safe',
        'value' => 'dry'
    ];

    const MUDDY = [
        'label' => 'Muddy',
        'value' => 'muddy'
    ];

    const WET = [
        'label' => 'Wet',
        'value' => 'wet'
    ];
}
