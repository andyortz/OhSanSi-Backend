<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            ['id_departamento' => 1, 'nombre_departamento' => 'Chuquisaca'],
            ['id_departamento' => 2, 'nombre_departamento' => 'La Paz'],
            ['id_departamento' => 3, 'nombre_departamento' => 'Cochabamba'],
            ['id_departamento' => 4, 'nombre_departamento' => 'Oruro'],
            ['id_departamento' => 5, 'nombre_departamento' => 'Potosí'],
            ['id_departamento' => 6, 'nombre_departamento' => 'Tarija'],
            ['id_departamento' => 7, 'nombre_departamento' => 'Santa Cruz'],
            ['id_departamento' => 8, 'nombre_departamento' => 'Beni'],
            ['id_departamento' => 9, 'nombre_departamento' => 'Pando'],
        ];

        DB::table('departamentos')->insert($departamentos);
    }
}
