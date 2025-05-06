<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $niveles =[
            ['nombre'=>'1P'],
            ['nombre'=>'2P'],
            ['nombre'=>'3P'],
            ['nombre'=>'4P'],
            ['nombre'=>'5P'],
            ['nombre'=>'6P'],
            ['nombre'=>'1S'],
            ['nombre'=>'2S'],
            ['nombre'=>'3S'],
            ['nombre'=>'4S'],
            ['nombre'=>'5S'],
            ['nombre'=>'6S'],
            ['nombre'=>'Guacamayo'],
            ['nombre'=>'Guanaco'],
            ['nombre'=>'Londra'],
            ['nombre'=>'Jucumari'],
            ['nombre'=>'Guacamayo'],
            ['nombre'=>'Guanaco'],
            ['nombre'=>'Bufeo'],
            ['nombre'=>'Puma'],
            ['nombre'=>'Primer Nivel '],
            ['nombre'=>'Segundo Nivel'],
            ['nombre'=>'Tercer Nivel'],
            ['nombre'=>'Cuarto Nivel'],
            ['nombre'=>'Quinto Nivel'],
            ['nombre'=>'Sexto Nivel'],
            ['nombre'=>'Builders P'],
            ['nombre'=>'Builders S'],
            ['nombre'=>'Lego P '],
            ['nombre'=>'Lego S '],
        ];

        DB::table('nivel_categoria')->insert($niveles);
    }
}
