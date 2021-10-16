<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\tenant\Especie;
class EspeciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $especies = [
            'BACUNO O BOVINO',
            'OVINO',
            'PORCINO',
            'CAPRINO',
            'CANINO',
            'EQUINO',
            'FELINO',
            'LEPORIDOS'
        ];
        foreach ($especies as $key => $value) {
            Especie::create([
                'name' => $value
            ]);
        }
    }
}
