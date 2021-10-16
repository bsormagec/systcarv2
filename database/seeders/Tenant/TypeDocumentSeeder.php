<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\tenant\Type;
class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Type::where('tipo','document')->count() == 0) {
            $documentos = [
                [
                    'name' => 'CARNET DE IDENTIDAD',
                    'tipo' => 'document'
                ],
                [
                    'name' => 'NIT',
                    'tipo' => 'document'
                ],
                [
                    'name' => 'RUT',
                    'tipo' => 'document'
                ],
                [
                    'name' => 'Nº PASAPORTE',
                    'tipo' => 'document'
                ],
                [
                    'name' => 'LIBRETA DE SERVICIO MILITAR',
                    'tipo' => 'document'
                ]
           ];
            foreach ($documentos as $document) {
                Type::create($document);
            }
        }
        
    }
}
