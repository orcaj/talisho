<?php


namespace App\Enum;


use Illuminate\Support\Str;

trait HasLabelArray
{
    public static function toLabelArray()
    {
        return self::collection()->flatMap(function($value) {
            return [$value => Str::title($value)];
        });
    }
}
