<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColegiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colegios = [
            ['nombre_colegio' => 'Unidad Educativa Buenas Nuevas', 'provincia' =>1],
            ['nombre_colegio' => 'Colegio Alemán Mariscal Braun', 'provincia'=>2],
            ['nombre_colegio' => 'Colegio Don Bosco', 'provincia'=> 3],
            ['nombre_colegio' => 'Colegio La Salle', 'provincia'=> 4],
            ['nombre_colegio' => 'Unidad Educativa Santa Ana', 'provincia'=>5],
            ['nombre_colegio' => 'Colegio Santa María', 'provincia'=> 6],
            ['nombre_colegio' => 'Unidad Educativa Copacabana', 'provincia'=>7],
            ['nombre_colegio' => 'Colegio Franco Boliviano', 'provincia'=>8],
            ['nombre_colegio' => 'Unidad Educativa Bolivia', 'provincia'=> 9],
            ['nombre_colegio' => 'Colegio San Agustín', 'provincia'=> 10],
            ['nombre_colegio' => 'Unidad Educativa América', 'provincia'=>11],
            ['nombre_colegio' => 'Colegio Domingo Savio', 'provincia'=> 12],
            ['nombre_colegio' => 'Unidad Educativa Mariscal Sucre', 'provincia'=>13],
            ['nombre_colegio' => 'Colegio Santa Cruz', 'provincia'=>14],
            ['nombre_colegio' => 'Unidad Educativa Potosí', 'provincia'=> 15],
            ['nombre_colegio' => 'Colegio Cardenal Maurer', 'provincia'=>16],
            ['nombre_colegio' => 'Unidad Educativa Loyola', 'provincia'=>17],
            ['nombre_colegio' => 'Colegio San Ignacio', 'provincia'=>18],
            ['nombre_colegio' => 'Unidad Educativa San Francisco', 'provincia'=>19],
            ['nombre_colegio' => 'Colegio La Paz', 'provincia'=>20],
            ['nombre_colegio' => 'Unidad Educativa San José', 'provincia'=>21],
            ['nombre_colegio' => 'Colegio Santa Teresa', 'provincia'=>22],
            ['nombre_colegio' => 'Unidad Educativa San Juan', 'provincia'=>23],
            ['nombre_colegio' => 'Colegio San Vicente', 'provincia'=>24],
            ['nombre_colegio' => 'Unidad Educativa San Pedro', 'provincia'=>25],
            ['nombre_colegio' => 'Colegio San Rafael', 'provincia'=>26],
            ['nombre_colegio' => 'Unidad Educativa San Pablo', 'provincia'=>27],
            ['nombre_colegio' => 'Colegio Británico', 'provincia'=>28],
            ['nombre_colegio' => 'Unidad Educativa Juan Pablo II', 'provincia'=> 29],
        ];

        DB::table('colegios')->insert($colegios);
    }
}
