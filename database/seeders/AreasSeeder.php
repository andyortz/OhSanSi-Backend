<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $areas =[
            ['id_area'=>1, 'nombre'=>'MATEMÁTICAS'],
            ['id_area'=>2, 'nombre'=>'BIOLOGÍA'],
            ['id_area'=>3, 'nombre'=>'ASTRONOMÍA - ASTROFÍSICA'],
            ['id_area'=>4, 'nombre'=>'ROBÓTICA'],
            ['id_area'=>5, 'nombre'=>'INFORMÁTICA'],
            ['id_area'=>6, 'nombre'=>'QUÍMICA'],
            // ['nombre'=>'Geografia'],
            // ['nombre'=>'Historia'],
        ];

        DB::table('areas_competencia')->insert($areas);
    }
}
