<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\tenant\Raza;

class RazasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $razas = [
            //razas de caballares
            [
                'name'=>'Caballo Andaluz',
                'especie_id' => 6
            ],
            [
                'name'=>'Caballos árabes',
                'especie_id' => 6            
            ],
            [
                'name'=>'Caballos de raza frisón',
                'especie_id' => 6
            ],
            [
                'name'=>'Caballos percherones',
                'especie_id' => 6
            ],
            [
                'name'=>'Caballos pintos',
                'especie_id' => 6
            ],
            [
                'name'=>'Caballo pura sangre inglés',
                'especie_id' => 6
            ],
            [
                'name'=>'Caballos Palomino',
                'especie_id' => 6
            ],
            [
                'name'=>'Caballo cuarto de mila',
                'especie_id' => 6
            ],
            [
                'name'=>'caballo Criollo',
                'especie_id' => 6
            ],
            [
                'name'=>'caballo pura sangre',
                'especie_id' => 6
            ],
            [
                'name'=>'caballo andaluz',
                'especie_id' => 6
            ],
            //razas de perros
            [
                'name'=>'Pastor Aleman',
                'especie_id' => 5
            ],
            [
                'name'=>'Buldog',
                'especie_id' => 5
            ],
            [
                'name'=>'Terriers',
                'especie_id' => 5
            ],

            [
                'name'=>'Calgo Español',
                'especie_id' => 5
            ],
            [
                'name'=>'Chihuahua',
                'especie_id' => 5
            ],
            [
                'name'=>'Bichon Boloñes',
                'especie_id' => 5
            ],
            [
                'name'=>'Bichon Maltes',
                'especie_id' => 5
            ],
            [
                'name'=>'Boxer',
                'especie_id' => 5
            ],
            [
                'name'=>'Dogo Argentino',
                'especie_id' => 5
            ],
            [
                'name' => 'Pit bull',
                'especie_id' => 5
            ],
            [
                'name' => 'Rottweiler',
                'especie_id' => 5
            ],
            [
                'name' => 'Fila brasileiro',
                'especie_id' => 5
            ],
            [
                'name' => 'Salchicha',
                'especie_id' => 5
            ],
            //razas de gatos
            [
                'name' => 'Burmilla',
                'especie_id' => 7
            ],
            [
                'name' => 'Curl Americano',
                'especie_id' => 7
            ],
            [
                'name' => 'Montés',
                'especie_id' => 7
            ],
            [
                'name' => 'Burmés',
                'especie_id' => 7
            ],
            [
                'name' => 'Munchkin',
                'especie_id' => 7
            ],
            [
                'name' => 'Shausie',
                'especie_id' => 7
            ],
            [
                'name' => 'Devon Rex',
                'especie_id' => 7
            ],
            [
                'name' => 'Oriental de pelo largo o javanés',
                'especie_id' => 7
            ],
            [
                'name' => 'Scottish Fold',
                'especie_id' => 7
            ],
            [
                'name' => 'Van Turco',
                'especie_id' => 7
            ],
            [
                'name' => 'Korat',
                'especie_id' => 7
            ],
            [
                'name' => 'Somalí',
                'especie_id' => 7
            ],
            [
                'name' => 'Sphynx o Esfinge',
                'especie_id' => 7
            ],
            [
                'name' => 'Savannah',
                'especie_id' => 7
            ],
            [
                'name' => 'Cartujo o Chartreux',
                'especie_id' => 7
            ],
            [
                'name' => 'Sokoke',
                'especie_id' => 7
            ],
            [
                'name' => 'Selkirk Rex',
                'especie_id' => 7
            ],
            [
                'name' => 'Nebelung',
                'especie_id' => 7
            ],
            [
                'name' => 'Lykoi o gato lobo',
                'especie_id' => 7
            ],
            [
                'name' => 'Cornish Rex',
                'especie_id' => 7
            ],
            [
                'name' => 'Ocicat o gato ocelote',
                'especie_id' => 7
            ],
            [
                'name' => 'Peterbald',
                'especie_id' => 7
            ],
            [
                'name' => 'Oriental de pelo corto',
                'especie_id' => 7
            ],
            [
                'name' => 'Siberiano',
                'especie_id' => 7
            ],
            [
                'name' => 'Manx',
                'especie_id' => 7
            ],
            [
                'name' => 'Exótico de pelo corto',
                'especie_id' => 7
            ],
            [
                'name' => 'Birmano',
                'especie_id' => 7
            ],
            [
                'name' => 'Bosque de Noruega',
                'especie_id' => 7
            ],
            [
                'name' => 'Snowshoe',
                'especie_id' => 7
            ],
            [
                'name' => 'De Bengala o Bengalí',
                'especie_id' => 7
            ],
            [
                'name' => 'Ashera',
                'especie_id' => 7
            ],
            [
                'name' => 'Abisinio',
                'especie_id' => 7
            ],
            [
                'name' => 'Balinés',
                'especie_id' => 7
            ],
            [
                'name' => 'Maine Coon',
                'especie_id' => 7
            ],
            [
                'name' => 'British shorthair',
                'especie_id' => 7
            ],
            [
                'name' => 'Azul Ruso',
                'especie_id' => 7
            ],
            [
                'name' => 'Bombay',
                'especie_id' => 7
            ],
            [
                'name' => 'Europeo',
                'especie_id' => 7
            ],
            [
                'name' => 'Mau Egipcio',
                'especie_id' => 7
            ],
            [
                'name' => 'Australian Mist',
                'especie_id' => 7
            ],
            [
                'name' => 'Himalayo',
                'especie_id' => 7
            ],
            [
                'name' => 'Habana',
                'especie_id' => 7
            ],
            [
                'name' => 'Persa',
                'especie_id' => 7
            ],
            [
                'name' => 'Siamés',
                'especie_id' => 7
            ],
            [
                'name' => 'Ragdoll',
                'especie_id' => 7
            ],
            //raza de ganado
            [
                'name' => 'Belga Azul',
                'especie_id' => 1
            ],
            [
                'name' => 'Romagnola',
                'especie_id' => 1
            ],
            [
                'name' => 'Lenore',
                'especie_id' => 1
            ],
            [
                'name' => 'Marchigiana',
                'especie_id' => 1
            ],
            [
                'name' => 'Maine Anjou',
                'especie_id' => 1
            ],
            [
                'name' => 'Limousing',
                'especie_id' => 1
            ],
            [
                'name' => 'Holstein',
                'especie_id' => 1
            ],
            [
                'name' => 'Hereford',
                'especie_id' => 1
            ],
            [
                'name' => 'Gyr',
                'especie_id' => 1
            ],
            [
                'name' => 'Guserat',
                'especie_id' => 1
            ],
            [
                'name' => 'Gelbvieh',
                'especie_id' => 1
            ],
            //razas de conejos
            [
                'name' => 'Rex',
                'especie_id' => 8
            ],
            [
                'name' => 'Blanco de Hotot',
                'especie_id' => 8
            ],
            [
                'name' => 'Cabeza de león',
                'especie_id' => 8
            ],
            [
                'name' => 'Belier',
                'especie_id' => 8
            ],
            [
                'name' => 'English Angora',
                'especie_id' => 8
            ],
            [
                'name' => 'Toy o enano',
                'especie_id' => 8
            ],
            [
                'name' => 'Gigante de Flandes',
                'especie_id' => 8
            ],
            [
                'name' => 'Tan',
                'especie_id' => 8
            ],
            [
                'name' => 'Conejo gran chinchilla',
                'especie_id' => 8
            ],
            [
                'name' => 'Mariposa',
                'especie_id' => 8
            ],
            [
                'name' => 'Conejo holandés enano',
                'especie_id' => 8
            ],
            [
                'name' => 'Conejo arlequín',
                'especie_id' => 8
            ],
            [
                'name' => 'Conejo gigante continental',
                'especie_id' => 8
            ]
        ];

        foreach ($razas as $raza) {
            Raza::create($raza);
         }
    }
}
