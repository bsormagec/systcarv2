<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;

class SpecialityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $especialidades = [
            'Anestesiología Veterinaria',
            'Cardiología veterinaria',
            'Ciencias veterinarias o Animales',
            'Cirugía',
            'Dermatología',
            'Fisioterapia',
            'Neurología',
            'Oftalmología veterinaria',
            'Oncología Veterinaria',
            'Ortopedia',
            'Medicina de especies menores (pequeñas especies)',
            'Medicina de Felinos',
            'Medicina de especies mayores (grandes especies)',
            'Medicina de equinos',
            'Medicina de Rumiantes',
            'Medicina de aves',
            'Medicina de Fauna silvestre',
            'Medicina de conservación',
            'Ictiopatología',
            'Anatomía animal',
            'Biología Molecular y Genética',
            'Bioquímica',
            'Ciencias Biomédicas o Biotecnología',
            'Farmacología y Toxicología',
            'Fisiología',
            'Inmunología',
            'Microbiología',
            'Parasitología',
            'Patología Veterinaria',
            'Patología Clínica veterinaria o medicina de laboratorio',
            'Radiología veterinaria',
            'Ultrasonografía',
            'Ciencia y Tecnología de alimentos',
            'Epidemiología',
            'Sanidad Animal',
            'Salud Pública',
            'Acupuntura veterinaria',
            'Fitoterapia',
            'Flores de bach',
            'La homeopatía',
            'Kinesiología veterinaria',
            'Nutriterapia',
            'Administración de empresas Pecuarias',
            'Nutrición animal',
            'Producción Bovina',
            'Producción ovina y caprina',
            'Producción Porcina',
            'Producción avícola',
            'Producción piscícola o acuícola',
            'Reproducción Animal',
            'Bienestar Animal'
        ];

        foreach ($especialidades as $key => $value) {
            \App\Models\tenant\Speciality::create([
                'name' => $value
            ]);
        }
    }
}
