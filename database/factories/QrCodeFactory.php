<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\QrCode;
use App\Enum\QrCodeType;
use Faker\Generator as Faker;

$factory->define(QrCode::class, function (Faker $faker) {
    return [
        'link' => $faker->url,
        'type' => QrCodeType::PUBLIC,
        'slug' => implode("-", $faker->words(2)),
        'description' => $faker->sentence(5),
    ];
});
