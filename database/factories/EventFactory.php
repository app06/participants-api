<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'date' => $faker->unique()->dateTimeBetween('now', '+100 days'),
        'city' => $faker->city
    ];
});
