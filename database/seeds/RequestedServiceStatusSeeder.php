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
            'description' => 'El viaje tiene este estado cuando esta pendiente de aprobar por el cliente.',
        ]);

        factory(RequestedServiceStatus::class)->create([
            'name'        => 'Aceptado',
            'description' => 'El viaje tiene este estado cuando el cliente aprueba el recorrido.',
        ]);

        factory(RequestedServiceStatus::class)->create([
            'name'        => 'Rechazado',
            'description' => 'El viaje tiene este estado cuando el transportista rechaza el recorrido.',
        ]);

        factory(RequestedServiceStatus::class)->create([
            'name'        => 'Iniciado',
            'description' => 'El viaje tiene este estado cuando el transportista inicia el recorrido',
        ]);

        factory(RequestedServiceStatus::class)->create([
            'name'        => 'Finalizado',
            'description' => 'El viaje tiene este estado cuando el transportista culmina el recorrido.',
        ]);
    }
}
