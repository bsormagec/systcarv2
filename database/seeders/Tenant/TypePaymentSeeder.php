<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\tenant\Type;

class TypePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipospagos = [
            [
               'name' => 'TRANSFERENCIA',
               'description' => 'Pago con Transferencia Bancaria',
               'tipo' => 'payment'
            ],
            [
                'name' => 'CASH',
                'description' => 'Pago al Contado',
                'tipo' => 'payment'
            ],
            [
                'name' => 'DEPOSITO',
                'description' => 'Deposito a nro de cuenta',
                'tipo' => 'payment'
            ],    
            [
                'name' => 'CREDITO',
                'description' => 'Pago a credito a la empresa',
                'tipo' => 'payment'
            ],  
        ];

        foreach ($tipospagos as $pago) {
            Type::create($pago);
         }
    }
}
