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
        ];

        DB::table('niveles_categoria')->insert($niveles);
    }
}
