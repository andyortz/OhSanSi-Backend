<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use App\Models\Tutor;

class TutoresSeeder extends Seeder
{
    public function run(): void
    {
        $tutores = [
            ['nombres' => 'Rudolf', 'apellidos' => 'Mamani', 'ci' => 3718953, 'celular' => 16815249, 'correo_electronico' => 'Rudolfcito@gmail.com'],
            ['nombres' => 'Albert', 'apellidos' => 'Quispe', 'ci' => 785392561, 'celular' => 79246923, 'correo_electronico' => 'albert@gmail.com'],
            ['nombres' => 'Diego', 'apellidos' => 'Lizard', 'ci' => 4672351, 'celular' => 78235612, 'correo_electronico' => 'Diego23@gmail,com'],
            ['nombres' => 'Lady', 'apellidos' => 'Jackson Sanchez', 'ci' => 93274325, 'celular' => 46232854, 'correo_electronico' => 'gaga@gmail.com'],
            ['nombres' => 'Farieed', 'apellidos' => 'Chino Llusco', 'ci' => 9467821, 'celular' => 7894729, 'correo_electronico' => 'chinito@gmail.com'],
            ['nombres' => 'Judas', 'apellidos' => 'Abracadabra', 'ci' => 3321159, 'celular' => 54323719, 'correo_electronico' => 'judas@gmail.com'],
            ['nombres' => 'Bruno', 'apellidos' => 'Mars Quispe', 'ci' => 12321159, 'celular' => 54211319, 'correo_electronico' => 'ElMarciano@gmail.com'],
            ['nombres' => 'Evo', 'apellidos' => 'Morales', 'ci' => 58492015, 'celular' => 68323719, 'correo_electronico' => 'papaEvo@gmail.com'],
            ['nombres' => 'Tuto', 'apellidos' => 'Linera', 'ci' => 47286959, 'celular' => 73052167, 'correo_electronico' => 'tutitopro@gmail.com'],
        ];
        DB::table('tutores')->insert($tutores);
    }
}
