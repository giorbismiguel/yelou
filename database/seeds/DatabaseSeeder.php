<?php

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Set env
        $originalEnv = config('app.env');
        config(['app.env' => 'migration']);

        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $this->call(LicenseTypesSeeder::class);
        $this->call(PaymentMethodSeeder::class);

        factory(User::class)->state('administrator')->create([
            'name'              => 'Super Administrador',
            'email'             => 'super_administrator@yelou.com',
            'password'          => bcrypt('Yelou20*-+/19'),
            'first_name'        => 'Super Administrador',
            'phone_verified_at' => today(),
        ]);

        if ($originalEnv !== 'production') {
            $this->call(UserSeeder::class);
            $this->call(RequestedServiceStatusSeeder::class);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        Model::reguard();

        config(['app.env' => $originalEnv]);
    }
}
