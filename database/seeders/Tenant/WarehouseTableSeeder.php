<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\tenant\Warehouse;
use App\Models\tenant\Sucursal;
class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Sucursal::count() == 0) {
            $sucursal = Sucursal::create([
                'name' => 'Sucursal Central',
                'address' => 'NN'
            ]);
          
            Warehouse::create([
                'nombre' => 'Almacen Central',
                'direccion' => 'NN',
                'sucursal_id' => $sucursal->id
            ]);
        }   
    }
}
