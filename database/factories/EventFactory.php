<?php

use Faker\Generator as Faker;

$factory->define(App\Event::class, function (Faker $faker) {
    return [
        'name' => $faker->words(3, true),
        'slug' => function ($event) {
            return \Illuminate\Support\Str::slug($event['name']);
        },
        'description' => $faker->paragraph,
        'starts_at' => $faker->dateTime,
        'ends_at' => $faker->dateTime,
        'venue_id' => function () {
            return factory(App\Venue::class)->create()->id;
        }
    ];
});
