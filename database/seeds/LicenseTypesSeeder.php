<?php

use Illuminate\Database\Seeder;

class LicenseTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('license_types')->truncate();

        $rows = [
            [
                'id'          => 1,
                'type'        => 'C',
                'description' => <<<WOF
Para taxis convencionales, ejecutivos, camionetas livianas o mixta hasta 3.500 kg, hasta 8 pasajeros; 
vehiculos de transporte de pasajeros de no mas de 25 asientos y los vehículos comprendidos en el tipo B.
WOF
            ],
            [
                'id'          => 2,
                'type'        => 'C1',
                'description' => <<<WOF
Para vehiculos policiales, ambulancias militares, municipales, y en general todo vehiculo del 
Estado ecuatoriano de emergencia y control de seguridad
WOF
            ],
        ];

        foreach ($rows as $row) {
            DB::table('license_types')->insert($row);
        }
    }
}
