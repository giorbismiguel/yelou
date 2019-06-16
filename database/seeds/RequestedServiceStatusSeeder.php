<?php

use App\Models\Admin\RequestedServiceStatus;
use Illuminate\Database\Seeder;

class RequestedServiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(RequestedServiceStatus::class)->create([
            'name'        => 'Pendiente',
            'description' => 'El estado esta pendiente de aprobar por el cliente',
        ]);

        factory(RequestedServiceStatus::class)->create([
            'name'        => 'Aceptado',
            'description' => 'El estado ha sido aceptado por el cliente',
        ]);

        factory(RequestedServiceStatus::class)->create([
            'name'        => 'Rechazado',
            'description' => 'El estado ha sido rechazado por el cliente',
        ]);
    }
}
