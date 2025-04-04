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
            ['id_colegio' => 1, 'nombre_colegio' => 'Unidad Educativa Buenas Nuevas'],
            ['id_colegio' => 2, 'nombre_colegio' => 'Colegio Alemán Mariscal Braun'],
            ['id_colegio' => 3, 'nombre_colegio' => 'Unidad Educativa San Ignacio'],
            ['id_colegio' => 4, 'nombre_colegio' => 'Colegio Don Bosco'],
            ['id_colegio' => 5, 'nombre_colegio' => 'Colegio La Salle'],
            ['id_colegio' => 6, 'nombre_colegio' => 'Unidad Educativa Santa Ana'],
            ['id_colegio' => 7, 'nombre_colegio' => 'Colegio Santa María'],
            ['id_colegio' => 8, 'nombre_colegio' => 'Unidad Educativa Copacabana'],
            ['id_colegio' => 9, 'nombre_colegio' => 'Colegio Franco Boliviano'],
            ['id_colegio' => 10, 'nombre_colegio' => 'Unidad Educativa Bolivia'],
            ['id_colegio' => 11, 'nombre_colegio' => 'Colegio San Agustín'],
            ['id_colegio' => 12, 'nombre_colegio' => 'Unidad Educativa América'],
            ['id_colegio' => 13, 'nombre_colegio' => 'Colegio Domingo Savio'],
            ['id_colegio' => 14, 'nombre_colegio' => 'Unidad Educativa Mariscal Sucre'],
            ['id_colegio' => 15, 'nombre_colegio' => 'Colegio Santa Cruz'],
            ['id_colegio' => 16, 'nombre_colegio' => 'Unidad Educativa Potosí'],
            ['id_colegio' => 17, 'nombre_colegio' => 'Colegio Cardenal Maurer'],
            ['id_colegio' => 18, 'nombre_colegio' => 'Unidad Educativa Loyola'],
            ['id_colegio' => 19, 'nombre_colegio' => 'Colegio Británico'],
            ['id_colegio' => 20, 'nombre_colegio' => 'Unidad Educativa Juan Pablo II'],
        ];

        DB::table('colegios')->insert($colegios);
    }
}
