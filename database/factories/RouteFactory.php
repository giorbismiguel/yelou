<?php

use Faker\Generator as Faker;

/**@var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Route::class, function (Faker $faker) {
    return [
        'name'              => $faker->name,
        'lat'               => $faker->latitude,
        'lng'               => $faker->longitude,
        'formatted_address' => $faker->address,
        'user_id'           => factory(App\User::class)->lazy()
    ];
});