<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Enrollments\Models\Inscripcion;
use App\Modules\Enrollments\Models\Pago;
use Illuminate\Support\Facades\DB;

class InscripcionSeeder extends Seeder
{
    public function run()
    {
        $pagos = Pago::inRandomOrder()->take(5)->pluck('id_pago');

        foreach ($pagos as $idPago) {
            Inscripcion::create([
                'id_olimpiada' => 1, 
                'id_detalle_olimpista' => rand(5, 10), 
                'ci_tutor_academico' => rand(1, 5), 
                'id_pago' => $idPago,
                'id_nivel' => rand(1, 12), 
                'estado' => 'inscrito', 
                'fecha_inscripcion' => now()->subDays(rand(1, 20)),
            ]);
        }
    }
}
