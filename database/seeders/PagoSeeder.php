<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class PagoSeeder extends Seeder
{
    public function run()
    {
        // Obtener algunos ci_persona aleatorios
        $personas = Persona::inRandomOrder()->take(5)->pluck('ci_persona');

        foreach ($personas as $index => $ciPersona) {
            Pago::create([
                'comprobante' => 'COMP-' . strtoupper(uniqid()),
                'fecha_pago' => now()->subDays(rand(1, 30)),
                'ci_responsable_inscripcion' => $ciPersona,
                'monto_pagado' => rand(100, 500),
                'verificado' => (bool)rand(0, 1),
                'verificado_en' => now(),
                'verificado_por' => 'admin_user_' . ($index + 1),
            ]);
        }
    }
}
