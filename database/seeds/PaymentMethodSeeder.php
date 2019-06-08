<?php

use App\Models\Admin\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PaymentMethod::class)->create(['name' => 'Transferencia Bancaria']);
        factory(PaymentMethod::class)->create(['name' => 'Tarjeta de Crédito o Prepago']);
        factory(PaymentMethod::class)->create(['name' => 'Pago en Efectivo']);
    }
}
