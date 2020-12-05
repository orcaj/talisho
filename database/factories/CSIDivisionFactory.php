<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CSIDivision;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(CSIDivision::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => Str::random(2)
    ];
});
