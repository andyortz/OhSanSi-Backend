<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradosSeeder extends Seeder
{
    public function run(): void
    {
        $grados = [
            ['nombre_grado' => '1ro Primaria', 'nivel_academico' => 'Primaria', 'orden' => 1],
            ['nombre_grado' => '2do Primaria', 'nivel_academico' => 'Primaria', 'orden' => 2],
            ['nombre_grado' => '3ro Primaria', 'nivel_academico' => 'Primaria', 'orden' => 3],
            ['nombre_grado' => '4to Primaria', 'nivel_academico' => 'Primaria', 'orden' => 4],
            ['nombre_grado' => '5to Primaria', 'nivel_academico' => 'Primaria', 'orden' => 5],
            ['nombre_grado' => '6to Primaria', 'nivel_academico' => 'Primaria', 'orden' => 6],
            ['nombre_grado' => '1ro Secundaria', 'nivel_academico' => 'Secundaria', 'orden' => 7],
            ['nombre_grado' => '2do Secundaria', 'nivel_academico' => 'Secundaria', 'orden' => 8],
            ['nombre_grado' => '3ro Secundaria', 'nivel_academico' => 'Secundaria', 'orden' => 9],
            ['nombre_grado' => '4to Secundaria', 'nivel_academico' => 'Secundaria', 'orden' => 10],
            ['nombre_grado' => '5to Secundaria', 'nivel_academico' => 'Secundaria', 'orden' => 11],
            ['nombre_grado' => '6to Secundaria', 'nivel_academico' => 'Secundaria', 'orden' => 12],
        ];

        DB::table('grados')->insert($grados);
    }
}
