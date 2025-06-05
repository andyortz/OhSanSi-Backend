<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $levels =[
            ['name'=>'1P'],
            ['name'=>'2P'],
            ['name'=>'3P'],
            ['name'=>'4P'],
            ['name'=>'5P'],
            ['name'=>'6P'],
            ['name'=>'1S'],
            ['name'=>'2S'],
            ['name'=>'3S'],
            ['name'=>'4S'],
            ['name'=>'5S'],
            ['name'=>'6S'],
            ['name'=>'GUACAMAYO'],
            ['name'=>'GUANACO'],
            ['name'=>'LONDRA'],
            ['name'=>'JUCUMARI'],
            ['name'=>'BUFEO'],
            ['name'=>'PUMA'],
            ['name'=>'PRIMER NIVEL'],
            ['name'=>'SEGUNDO NIVEL'],
            ['name'=>'TERCER NIVEL'],
            ['name'=>'CUARTO NIVEL'],
            ['name'=>'QUINTO NIVEL'],
            ['name'=>'SEXTO NIVEL'],
            ['name'=>'BUILDERS P'],
            ['name'=>'BUILDERS S'],
            ['name'=>'LEGO P '],
            ['name'=>'LEGO S '],
        ];

        DB::table('category_level')->insert($levels);
    }
}
