<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Documentation;
use App\OtherDocument;
use Faker\Generator as Faker;

$factory->define(Documentation::class, function (Faker $faker) {
    return [
        'identifier' => $faker->word,
        'status' => \App\Enum\MessagingStatus::OUTSTANDING,
        'due_date' => null,
    ];
});
