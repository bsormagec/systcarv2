<?php

namespace Database\Seeders\Tenant;
use App\Models\tenant\Unit;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            //unidades de medidas
            [
                'nombre'=>'UNIDAD',
                'abreviacion' => 'Und.'
            ],
            [
                'nombre'=>'CAJA',
                'abreviacion' => 'Cj.'
            ],
            [
                'nombre'=>'KILOGRAMOS',
                'abreviacion' => 'Kg.'
            ],
            [
                'nombre'=>'LITROS',
                'abreviacion' => 'Lt.'
            ]
        ];
        if (Unit::count() == 0) {
            foreach ($units as $unit) {
                Unit::create($unit);
            }
        }
        
    }
}
