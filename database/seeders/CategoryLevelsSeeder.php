<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $levels =[
            ['level_name'=>'1P'],
            ['level_name'=>'2P'],
            ['level_name'=>'3P'],
            ['level_name'=>'4P'],
            ['level_name'=>'5P'],
            ['level_name'=>'6P'],
            ['level_name'=>'1S'],
            ['level_name'=>'2S'],
            ['level_name'=>'3S'],
            ['level_name'=>'4S'],
            ['level_name'=>'5S'],
            ['level_name'=>'6S'],
            ['level_name'=>'GUACAMAYO'],
            ['level_name'=>'GUANACO'],
            ['level_name'=>'LONDRA'],
            ['level_name'=>'JUCUMARI'],
            ['level_name'=>'BUFEO'],
            ['level_name'=>'PUMA'],
            ['level_name'=>'PRIMER NIVEL'],
            ['level_name'=>'SEGUNDO NIVEL'],
            ['level_name'=>'TERCER NIVEL'],
            ['level_name'=>'CUARTO NIVEL'],
            ['level_name'=>'QUINTO NIVEL'],
            ['level_name'=>'SEXTO NIVEL'],
            ['level_name'=>'BUILDERS P'],
            ['level_name'=>'BUILDERS S'],
            ['level_name'=>'LEGO P '],
            ['level_name'=>'LEGO S '],
        ];

        DB::table('category_levels')->insert($levels);
    }
}
