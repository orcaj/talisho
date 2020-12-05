<?php


namespace App\Enum;


class AccountStatus extends Enum
{
    use HasLabelArray;

    const ACTIVE = "ACTIVE";
    const INACTIVE = "INACTIVE";
}
