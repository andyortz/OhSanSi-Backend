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
            ['id_nivel'=>1,'nombre'=>'3P'],
            ['id_nivel'=>2,'nombre'=>'4P'],
            ['id_nivel'=>3,'nombre'=>'5P'],
            ['id_nivel'=>4,'nombre'=>'6P'],
            ['id_nivel'=>5,'nombre'=>'1S'],
            ['id_nivel'=>6,'nombre'=>'2S'],
            ['id_nivel'=>7,'nombre'=>'3S'],
            ['id_nivel'=>8,'nombre'=>'4S'],
            ['id_nivel'=>9,'nombre'=>'5S'],
            ['id_nivel'=>10,'nombre'=>'6S'],
        ];

        DB::table('niveles_categoria')->insert($niveles);
    }
}
