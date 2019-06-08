<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->state('client')->create([
            'name'              => 'Cliente',
            'email'             => 'cliente@yelou.com',
            'password'          => bcrypt('123456'),
            'first_name'        => 'Cliente',
            'phone_verified_at' => today(),
        ]);

        factory(User::class)->state('transportation')->create([
            'name'              => 'Transportista',
            'email'             => 'transportista@yelou.com',
            'password'          => bcrypt('123456'),
            'first_name'        => 'Transportista',
            'phone_verified_at' => today(),
        ]);

        factory(User::class)->state('administrator')->create([
            'name'              => 'administrador',
            'email'             => 'administrator@yelou.com',
            'password'          => bcrypt('123456'),
            'first_name'        => 'Administrador',
            'phone_verified_at' => today(),
        ]);

        factory(User::class, 100)->state('client')->create();

        factory(User::class, 100)->state('transportation')->create();
    }
}
