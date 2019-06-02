<?php

use Faker\Generator as Faker;

$factory->define(App\Route::class, function (Faker $faker) {
    return [
        'name'              => $faker->streetAddress,
        'lat'               => $faker->latitude,
        'lng'               => $faker->longitude,
        'formatted_address' => $faker->word
    ];
});