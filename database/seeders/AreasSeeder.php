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
            ['area_name'=>'MATEMÁTICAS'],
            ['area_name'=>'BIOLOGÍA'],
            ['area_name'=>'ASTRONOMÍA - ASTROFÍSICA'],
            ['area_name'=>'ROBÓTICA'],
            ['area_name'=>'INFORMÁTICA'],
            ['area_name'=>'QUÍMICA'],
            ['area_name'=>'FÍSICA'],
        ];

        DB::table('areas')->insert($areas);
    }
}
