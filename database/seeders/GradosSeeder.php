<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradosSeeder extends Seeder
{
    public function run(): void
    {
        $grados = [
            ['nombre_grado' => '1ro Primaria'],
            ['nombre_grado' => '2ro Primaria'],
            ['nombre_grado' => '3ro Primaria'],
            ['nombre_grado' => '4to Primaria'],
            ['nombre_grado' => '5to Primaria'],
            ['nombre_grado' => '6to Primaria'],
            ['nombre_grado' => '1ro Secundaria'],
            ['nombre_grado' => '2do Secundaria'],
            ['nombre_grado' => '3ro Secundaria'],
            ['nombre_grado' => '4to Secundaria'],
            ['nombre_grado' => '5to Secundaria'],
            ['nombre_grado' => '6to Secundaria'],
        ];

        DB::table('grados')->insert($grados);
    }

}


