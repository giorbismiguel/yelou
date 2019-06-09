<?php

use Faker\Generator as Faker;

/**@var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Route::class, function (Faker $faker) {
    return [
        'name'                    => $faker->streetName,
        'lat_start'               => $faker->latitude,
        'lng_start'               => $faker->longitude,
        'formatted_address_start' => $faker->address,
        'lat_end'                 => $faker->latitude,
        'lng_end'                 => $faker->longitude,
        'formatted_address_end'   => $faker->address,
        'user_id'                 => factory(App\User::class)->lazy()
    ];
});