<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $areas =[
            ['name'=>'MATEMÁTICAS'],
            ['name'=>'BIOLOGÍA'],
            ['name'=>'ASTRONOMÍA - ASTROFÍSICA'],
            ['name'=>'ROBÓTICA'],
            ['name'=>'INFORMÁTICA'],
            ['name'=>'QUÍMICA'],
            ['name'=>'FÍSICA'],
        ];

        DB::table('area')->insert($areas);
    }
}
