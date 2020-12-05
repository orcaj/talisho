<?php


namespace App\Enum;

use App\OtherDocument;
use App\Submittal;

class DocumentationResponseStatus extends Enum
{
    const NO_EXCEPTIONS_TAKEN = [
        'label' => 'No Exceptions Taken',
        'group' => Submittal::class,
        'approves' => true
    ];
    const MAKE_CORRECTIONS_NOTED = [
        'label' => 'Make Corrections Noted',
        'group' => Submittal::class,
        'approves' => true
    ];

    const REVISE_AND_RESUBMIT = [
        'label' => 'Revise and Resubmit',
        'group' => Submittal::class,
        'approves' => false
    ];

    const REJECTED = [
        'label' => 'Rejected',
        'group' => Submittal::class,
        'approves' => false
    ];

    const SUBMIT_SPECIFIED_ITEM = [
        'label' => 'Submit Specified Item',
        'group' => Submittal::class,
        'approves' => false
    ];

    const ACCEPTED = [
        'label' => 'Accepted',
        'group' => OtherDocument::class,
        'approves' => true
    ];

    const NOT_ACCEPTED = [
        'label' => 'Not Accepted',
        'group' => OtherDocument::class,
        'approves' => false
    ];
}
