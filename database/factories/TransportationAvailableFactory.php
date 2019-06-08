<?php

use Faker\Generator as Faker;

$factory->define(App\TransportationAvailable::class, function (Faker $faker) {
    return [
        'lat'     => $faker->latitude,
        'lng'     => $faker->longitude,
        'active'  => mt_rand(0, 1),
        'user_id' => factory(App\User::class)->lazy()
    ];
});