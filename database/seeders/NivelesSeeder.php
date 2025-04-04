<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NivelesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $niveles =[
            ['id_nivel'=>1,'nombre_nivel'=>'3P'],
            ['id_nivel'=>2,'nombre_nivel'=>'4P'],
            ['id_nivel'=>3,'nombre_nivel'=>'5P'],
            ['id_nivel'=>4,'nombre_nivel'=>'6P'],
            ['id_nivel'=>5,'nombre_nivel'=>'1S'],
            ['id_nivel'=>6,'nombre_nivel'=>'2S'],
            ['id_nivel'=>7,'nombre_nivel'=>'3S'],
            ['id_nivel'=>8,'nombre_nivel'=>'4S'],
            ['id_nivel'=>9,'nombre_nivel'=>'5S'],
            ['id_nivel'=>10,'nombre_nivel'=>'6S'],
        ];

        DB::class('niveles_categoria')->insert($niveles);
    }
}
