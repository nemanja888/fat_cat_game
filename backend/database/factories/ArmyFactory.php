<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Army;
use App\Game;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Army::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'units' => $faker->numberBetween($min = 80, $max = 100),
        'strategy' => $faker->randomElement(['random', 'weakest', 'strongest']),
    ];
});
