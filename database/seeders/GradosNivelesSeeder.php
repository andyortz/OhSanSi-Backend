<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradosNivelesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $gradoXnivel =[
            ['id_nivel'=>1, 'id_grado'=> 1],
            ['id_nivel'=>2, 'id_grado'=> 2],
            ['id_nivel'=>3, 'id_grado'=> 3],
            ['id_nivel'=>4, 'id_grado'=> 4],
            ['id_nivel'=>5, 'id_grado'=> 5],
            ['id_nivel'=>6, 'id_grado'=> 6],
            ['id_nivel'=>7, 'id_grado'=> 7],
            ['id_nivel'=>8, 'id_grado'=> 8],
            ['id_nivel'=>9, 'id_grado'=> 9],
            ['id_nivel'=>10, 'id_grado'=> 10],
        ];

        DB::class(grados_niveles)->insert($gradoXnivel);
    }
}
