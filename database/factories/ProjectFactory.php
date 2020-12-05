<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Project::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address_1' => $faker->streetAddress,
        'address_2' => $faker->optional()->secondaryAddress,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'zip' => $faker->postcode,
        'country' => 'US',
        'closed_at' => null,
        'client_name' => $faker->company,
        'description' => $faker->paragraph,
    ];
});


$factory->state(\App\Project::class, 'closed', function (Faker $faker) {
    return [
        'closed_at' => $faker->dateTime
    ];
});
