<?php


namespace App\Enum;


class ProjectLevelStatuses extends Enum
{
    const NO_ACTION = [
        'status' => 'No Action Needed',
        'color' => 'white',
        'priority' => 4
    ];

    const GOOD_STANDING = [
        'status' => 'Good Standing',
        'color' => 'green',
        'priority' => 3
    ];

    const WARNING = [
        'status' => 'Warning',
        'color' => 'yellow',
        'priority' => 2
    ];

    const ISSUE = [
        'status' => 'Issue',
        'color' => 'red',
        'priority' => 1
    ];
}
