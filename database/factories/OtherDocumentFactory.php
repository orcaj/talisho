<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OtherDocument;
use Faker\Generator as Faker;

$factory->define(OtherDocument::class, function (Faker $faker) {
    return [
        'submittal_id' => null,
        'specification_type' => \App\CSI::class,
        'specification_id' => factory(\App\CSI::class)->create(),
        'document_type' => \App\Enum\DocumentType::random(),
    ];
});
