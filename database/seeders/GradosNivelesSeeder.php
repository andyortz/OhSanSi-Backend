<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradosNivelesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('grados_niveles')->insert([
            ['id_nivel' => 1, 'id_grado' => 1],
            ['id_nivel' => 2, 'id_grado' => 2],
            ['id_nivel' => 3, 'id_grado' => 3],
            ['id_nivel' => 4, 'id_grado' => 4],
            ['id_nivel' => 5, 'id_grado' => 5],
            ['id_nivel' => 6, 'id_grado' => 6],
            ['id_nivel' => 7, 'id_grado' => 7],
            ['id_nivel' => 8, 'id_grado' => 8],
            ['id_nivel' => 9, 'id_grado' => 9],
            ['id_nivel' => 10, 'id_grado' => 10],
            ['id_nivel' => 11, 'id_grado' => 11],
            ['id_nivel' => 12, 'id_grado' => 12],
            ['id_nivel' => 1, 'id_grado' => 2],
            ['id_nivel' => 1, 'id_grado' => 3],
            ['id_nivel' => 21, 'id_grado' => 1],
            ['id_nivel' => 21, 'id_grado' => 2],
            ['id_nivel' => 21, 'id_grado' => 3],
            ['id_nivel' => 21, 'id_grado' => 4],
            ['id_nivel' => 21, 'id_grado' => 5],
            ['id_nivel' => 21, 'id_grado' => 6],
            ['id_nivel' => 20, 'id_grado' => 6],
            ['id_nivel' => 20, 'id_grado' => 7],
            ['id_nivel' => 20, 'id_grado' => 8],
        ]);
    }
}
