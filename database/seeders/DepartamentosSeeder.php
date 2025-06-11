<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            ['id_departamento' => 1, 'nombre_departamento' => 'CHUQUISACA'],
            ['id_departamento' => 2, 'nombre_departamento' => 'LA PAZ'],
            ['id_departamento' => 3, 'nombre_departamento' => 'COCHABAMBA'],
            ['id_departamento' => 4, 'nombre_departamento' => 'ORURO'],
            ['id_departamento' => 5, 'nombre_departamento' => 'POTOSI'],
            ['id_departamento' => 6, 'nombre_departamento' => 'TARIJA'],
            ['id_departamento' => 7, 'nombre_departamento' => 'SANTA CRUZ'],
            ['id_departamento' => 8, 'nombre_departamento' => 'BENI'],
            ['id_departamento' => 9, 'nombre_departamento' => 'PANDO'],
        ];

        DB::table('departamento')->insert($departamentos);
    }
}
