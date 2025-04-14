<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tutor;

class TutoresSeeder extends Seeder
{
    public function run(): void
    {
        Tutor::create([
            'nombres' => 'Leti',
            'apellidos' => 'Blanco',
            'ci' => 9,
            'celular' => 9,
            'correo_electronico' => 'leti',
        ]);
    }
}
