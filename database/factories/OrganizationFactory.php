<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Organization;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'address_1' => $faker->streetAddress,
        'address_2' => $faker->optional()->secondaryAddress,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'zip' => $faker->postcode,
        'country' => 'US',
        'phone' => $faker->phoneNumber,
        'website' => $faker->optional()->url,
        'account_type' => \App\Enum\AccountType::random(),
        'account_status' => \App\Enum\AccountStatus::random(),
        'employee_count_id' => \App\EmployeeCount::firstOrCreate(['label' => '1', 'sort' => 1]),
        'primary_contact_name' => $faker->name,
        'primary_contact_email' => $faker->safeEmail,
        'primary_contact_phone' => $faker->phoneNumber
    ];
});
