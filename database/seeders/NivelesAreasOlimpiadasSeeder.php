<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelesAreasOlimpiadasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nivel_area_olimpiada')->insert([
            [
                'id_olimpiada' => 1,
                'id_area' => 1,
                'id_nivel' => 1,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 1,
                'id_nivel' => 2,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 1,
                'id_nivel' => 3,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 2,
                'id_nivel' => 4,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 3,
                'id_nivel' => 13,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 5,
                'id_nivel' => 14,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 5,
                'id_nivel' => 21,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 4,
                'id_nivel' => 28,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 4,
                'id_nivel' => 27,
            ],
        ]);
    }
}
