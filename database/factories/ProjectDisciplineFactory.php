<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProjectDiscipline;
use Faker\Generator as Faker;

$factory->define(ProjectDiscipline::class, function (Faker $faker) {
    return [
        'project_id' => factory(\App\Project::class),
        'discipline_id' => \App\Discipline::all()->random(),
        'user_id' => factory(\App\User::class),
    ];
});
