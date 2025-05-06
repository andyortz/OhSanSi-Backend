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
            ['nombre'=>'MATEMÁTICAS'],
            ['nombre'=>'BIOLOGÍA'],
            ['nombre'=>'ASTRONOMÍA - ASTROFÍSICA'],
            ['nombre'=>'ROBÓTICA'],
            ['nombre'=>'INFORMÁTICA'],
            ['nombre'=>'QUÍMICA'],
            // ['nombre'=>'GEOGRAFIA'],
            // ['nombre'=>'HISTORIA'],
        ];

        DB::table('area_competencia')->insert($areas);
    }
}
