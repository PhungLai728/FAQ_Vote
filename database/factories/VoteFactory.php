<?php

use Faker\Generator as Faker;

$factory->define(App\Vote::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['up', 'down']),
    ];
});
