<?php

use App\TransportationAvailable;
use App\User;
use Faker\Generator as Faker;

/**@var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name'            => $faker->name,
        'email'           => $faker->unique()->email,
        'password'        => bcrypt('123456'),
        'first_name'      => $faker->firstName,
        'last_name'       => $faker->lastName,
        'phone'           => $faker->phoneNumber,
        'ruc'             => $faker->numerify('###########'),
        'direction'       => $faker->address,
        'type'            => mt_rand(1, 3), // 1- Client 2- Transportation 3- Admin
        'code_activation' => $faker->numerify('######'),
    ];
});

$factory->afterCreatingState(User::class, 'client', function (User $user, Faker $faker) {
    factory(\App\Route::class, 10)->create(['user_id' => $user->id]);
    $user->update([
        'type'        => 1, // 1- Client
        'city'        => $faker->city,
        'postal_code' => $faker->postcode,
    ]);
});

$factory->afterCreatingState(User::class, 'transportation', function (User $user) {
    factory(TransportationAvailable::class)->create(['user_id' => $user->id]);
    $user->update([
        'type'                         => 2, // 2- Transporter
        'license_types_id'             => mt_rand(1, 2), // 1- C 2- C1
        'photo'                        => null,
        'image_driver_license'         => null,
        'image_permit_circulation'     => null,
        'image_certificate_background' => null,
    ]);
});

$factory->afterCreatingState(User::class, 'administrator', function (User $user) {
    $user->update(['type' => 3]);
});