<?php


namespace App\Enum;


class AccountType extends Enum
{
    use HasLabelArray;

    const FREE = 'FREE';
    const PAID = 'PAID';
}
