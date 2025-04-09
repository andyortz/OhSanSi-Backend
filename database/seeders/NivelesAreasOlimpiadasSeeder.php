<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelesAreasOlimpiadasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('niveles_areas_olimpiadas')->insert([
            [
                'id_olimpiada' => 1,
                'id_area' => 4,
                'id_nivel' => 21,
                'max_niveles' => 1,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 1,
                'id_nivel' => 6,
                'max_niveles' => 1,
            ],
            [
                'id_olimpiada' => 1,
                'id_area' => 4,
                'id_nivel' => 20,
                'max_niveles' => 1,
            ],
        ]);
    }
}
