<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OlimpiadasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('olimpiada')->insert([
            [
                'gestion' => 2025,
                'costo' => 15.00,
                'fecha_inicio' => '2025-03-17',
                'fecha_fin' => '2025-08-27',
                'creado_en' => Carbon::now(),
                'max_categorias_olimpista' => 2,
                'nombre_olimpiada' => 'INVIERNO'
            ],
        ]);
    }
}
