<?php

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

        if ($originalEnv !== 'production') {
            $this->call(RouteSeeder::class);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        Model::reguard();

        config(['app.env' => $originalEnv]);
    }
}
