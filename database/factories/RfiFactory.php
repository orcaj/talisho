<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Rfi;
use Faker\Generator as Faker;

$factory->define(Rfi::class, function (Faker $faker) {
    return [
        'subject' => $faker->sentence,
        'question' => $faker->paragraph,
        'identifier' => $faker->sentence,
    ];
});
