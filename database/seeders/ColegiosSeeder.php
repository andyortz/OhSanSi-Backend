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
            ['nombre_colegio' => 'Unidad Educativa Buenas Nuevas'],
            ['nombre_colegio' => 'Colegio Alemán Mariscal Braun'],
            ['nombre_colegio' => 'Colegio Don Bosco'],
            ['nombre_colegio' => 'Colegio La Salle'],
            ['nombre_colegio' => 'Unidad Educativa Santa Ana'],
            ['nombre_colegio' => 'Colegio Santa María'],
            ['nombre_colegio' => 'Unidad Educativa Copacabana'],
            ['nombre_colegio' => 'Colegio Franco Boliviano'],
            ['nombre_colegio' => 'Unidad Educativa Bolivia'],
            ['nombre_colegio' => 'Colegio San Agustín'],
            ['nombre_colegio' => 'Unidad Educativa América'],
            ['nombre_colegio' => 'Colegio Domingo Savio'],
            ['nombre_colegio' => 'Unidad Educativa Mariscal Sucre'],
            ['nombre_colegio' => 'Colegio Santa Cruz'],
            ['nombre_colegio' => 'Unidad Educativa Potosí'],
            ['nombre_colegio' => 'Colegio Cardenal Maurer'],
            ['nombre_colegio' => 'Unidad Educativa Loyola'],
            ['nombre_colegio' => 'Colegio Británico'],
            ['nombre_colegio' => 'Unidad Educativa Juan Pablo II'],
        ];

        DB::table('colegios')->insert($colegios);
    }
}
