<?php


namespace App\Enum;


class QrCodeType extends Enum
{
    use HasLabelArray;

    const PUBLIC = 'PUBLIC';
    const INTERNAL = 'INTERNAL';
}
