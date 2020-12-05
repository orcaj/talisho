<?php


namespace App\Enum;


use phpDocumentor\Reflection\Types\Self_;

class DesignDocumentType extends Enum
{
    const ADDENDUM = [
        'singular' => 'Addendum',
        'plural' => 'Addenda'
    ];

    const DRAWING = [
        'singular'=> 'Drawing',
        'plural' => 'Drawings'
    ];
    const SPECIFICATION = [
        'singular' => 'Specification',
        'plural' => 'Specifications'
    ];

    public static function singular()
    {
        return self::collection()->map(function($type){
            return $type['singular'];
        })->all();
    }

    public static function getPluralizedFromSingular($type)
    {
        return self::collection()->first(function ($documentType) use ($type) {
            return $documentType['singular'] === $type;
        })['plural'];
    }
}
