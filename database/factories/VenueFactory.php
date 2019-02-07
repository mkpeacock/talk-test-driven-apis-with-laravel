<?php

use Faker\Generator as Faker;

$factory->define(App\Venue::class, function (Faker $faker) {
    return [
        'name' => $faker->words(3, true),
    ];
});
