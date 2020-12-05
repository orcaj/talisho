<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CSI;
use Faker\Generator as Faker;

$factory->define(CSI::class, function (Faker $faker) {
    return [
        'code' => \Illuminate\Support\Str::random(6),
        'name' => $faker->name,
        'csi_division_id' => factory(\App\CSIDivision::class)->create()
    ];
});
