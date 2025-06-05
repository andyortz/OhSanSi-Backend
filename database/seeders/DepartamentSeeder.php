<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentSeeder extends Seeder
{
    public function run(): void
    {
        $departaments = [
            ['id_departament' => 1, 'departament_name' => 'CHUQUISACA'],
            ['id_departament' => 2, 'departament_name' => 'LA PAZ'],
            ['id_departament' => 3, 'departament_name' => 'COCHABAMBA'],
            ['id_departament' => 4, 'departament_name' => 'ORURO'],
            ['id_departament' => 5, 'departament_name' => 'POTOSÍ'],
            ['id_departament' => 6, 'departament_name' => 'TARIJA'],
            ['id_departament' => 7, 'departament_name' => 'SANTA CRUZ'],
            ['id_departament' => 8, 'departament_name' => 'BENI'],
            ['id_departament' => 9, 'departament_name' => 'PANDO'],
        ];

        DB::table('departament')->insert($departaments);
    }
}
