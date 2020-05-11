<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use App\User;
use App\Tag;
use App\TaskStatus;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'status_id' => $faker->randomElement(TaskStatus::where('id' ,'>' ,0)->pluck('id')->toArray()),
        'creator_id' => $faker->randomElement(User::where('id' ,'>' ,0)->pluck('id')->toArray()),
        'assigned_to_id' => $faker->randomElement(User::where('id' ,'>' ,0)->pluck('id')->toArray()),
    ];
});
