<?php

namespace App\Services\Registers;

use App\Modules\Enrollments\Models\EnrollmentList;
use Illuminate\Support\Facades\DB;

class ListaInscripcionService
{
    public function crearLista(int $ci_responsable_inscripcion, int $id_olimpiada): ListaInscripcion
    {
        return DB::transaction(function () use ($ci_responsable_inscripcion, $id_olimpiada) {
            // Buscar si ya existe una lista con el mismo responsable y olimpiada
            return ListaInscripcion::create([
                'ci_responsable_inscripcion' => $ci_responsable_inscripcion,
                'id_olimpiada'               => $id_olimpiada,
                'estado'                     => 'PENDIENTE',
                'fecha_creacion_lista'       => now(),
            ]);

            // Crear una nueva lista
            return ListaInscripcion::create([
                'ci_responsable_inscripcion' => $ci_responsable_inscripcion,
                'id_olimpiada'               => $id_olimpiada,
                'estado'                     => 'PENDIENTE',
                'fecha_creacion_lista'       => now(),
            ]);
        });
    }
}
