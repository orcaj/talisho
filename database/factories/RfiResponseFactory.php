<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RfiResponse;
use Faker\Generator as Faker;

$factory->define(RfiResponse::class, function (Faker $faker) {
    return [
        'response' => $faker->paragraph,
    ];
});
