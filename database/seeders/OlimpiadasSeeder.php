<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OlimpiadasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('olimpiadas')->insert([
            [
                'gestion' => 2021,
                'costo' => 15.00,
                'fecha_inicio' => '2025-03-17',
                'fecha_fin' => '2025-03-27',
                'creado_en' => Carbon::now(),
                'max_categorias_olimpista' => 2,
            ],
            [
                'gestion' => 2024,
                'costo' => 20.00,
                'fecha_inicio' => '2025-02-25',
                'fecha_fin' => '2025-04-01',
                'creado_en' => Carbon::now(),
                'max_categorias_olimpista' => 2,
            ],
            [
                'gestion' => 2025,
                'costo' => 25.00,
                'fecha_inicio' => '2025-05-01',
                'fecha_fin' => '2025-05-15',
                'creado_en' => Carbon::now(),
                'max_categorias_olimpista' => 3,
            ],
        ]);
    }
}
