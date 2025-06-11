<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradosSeeder extends Seeder
{
    public function run(): void
    {
        $grados = [
            ['nombre_grado' => '1RO PRIMARIA'],
            ['nombre_grado' => '2DO PRIMARIA'],
            ['nombre_grado' => '3RO PRIMARIA'],
            ['nombre_grado' => '4TO PRIMARIA'],
            ['nombre_grado' => '5TO PRIMARIA'],
            ['nombre_grado' => '6TO PRIMARIA'],
            ['nombre_grado' => '1RO SECUNDARIA'],
            ['nombre_grado' => '2DO SECUNDARIA'],
            ['nombre_grado' => '3RO SECUNDARIA'],
            ['nombre_grado' => '4TO SECUNDARIA'],
            ['nombre_grado' => '5TO SECUNDARIA'],
            ['nombre_grado' => '6TO SECUNDARIA'],
        ];

        DB::table('grado')->insert($grados);
    }

}


