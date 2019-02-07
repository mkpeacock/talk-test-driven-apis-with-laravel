<?php

use Faker\Generator as Faker;

$factory->define(App\Talk::class, function (Faker $faker) {
    return [
        'name' => $faker->words(3, true),
        'description' => $faker->paragraph,
        'event_id' => function () {
            return factory(App\Event::class)->create()->id;
        }
    ];
});
