<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomSpecification;
use Faker\Generator as Faker;

$factory->define(CustomSpecification::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => \Illuminate\Support\Str::random(6),
        'type' => \App\Enum\DocumentType::random(),
        'organization_id' => factory(\App\Organization::class)
    ];
});
