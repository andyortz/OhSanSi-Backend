<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Olimpista;

class OlimpistasSeeder extends Seeder
{
    public function run(): void
    {
        Olimpista::create([
            'nombres' => 'Deyson',
            'apellidos' => 'Isa',
            'cedula_identidad' => 8,
            'fecha_nacimiento' => '2025-04-09',
            'correo_electronico' => 'deysonIsa@gmail.com',
            'unidad_educativa' => 1,
            'id_grado' => 6,
        ]);
    }
}
