<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Enrollments\Models\Inscripcion;
use App\Models\Pagos;
use App\Modules\Olympiads\Models\NivelCategoria;
use App\Models\Area;
use App\Http\Requests\StoreInscripcionRequest;
use Illuminate\Support\Facades\DB;

class InscripcionAreaController
{
    /**
     * Registra al olimpista en uno o más niveles de competencia.
     */
    public function store(Request $request)
{
    try {
        DB::beginTransaction();

        $niveles = $request->input('niveles', []);

        // Agrupar los niveles por id_area
        $nivelesPorArea = [];

        foreach ($niveles as $id_nivel) {
            $nivel = NivelCategoria::find($id_nivel);
            if (!$nivel) {
                return response()->json([
                    'message' => "El nivel con ID {$id_nivel} no existe.",
                ], 400);
            }

            $id_area = $nivel->id_area;
            $nivelesPorArea[$id_area][] = $id_nivel;
        }

        // Validar que no supere el límite por área
        foreach ($nivelesPorArea as $id_area => $nivelesEnArea) {
            $area = Area::find($id_area);
            if (!$area) {
                return response()->json([
                    'message' => "El área con ID {$id_area} no existe.",
                ], 400);
            }

            $limite = $area->limite_categoria ?? 1; // Valor por defecto: 1

            if (count($nivelesEnArea) > $limite) {
                return response()->json([
                    'message' => "El área '{$area->nombre}' permite un máximo de {$limite} categoría(s).",
                    'area_id' => $id_area,
                    'intento' => count($nivelesEnArea),
                ], 422);
            }
        }

        // Crear un pago temporal (si aplica)
        $pago = Pagos::create([
            'id_olimpiada' => $request->id_olimpiada,
            'comprobante' => 'registro_automático',
            'fecha_pago' => now(),
            'nombre_pagador' => 'Registro sin pago',
            'monto_pagado' => 0,
            'verificado' => false,
        ]);

        // Registrar cada inscripción
        foreach ($niveles as $id_nivel) {
            Inscripcion::create([
                'id_olimpiada' => $request->id_olimpiada,
                'id_olimpista' => $request->id_olimpista,
                'id_pago' => $pago->id_pago,
                'id_nivel' => $id_nivel,
                'estado' => $request->estado ?? 'PENDIENTE',
                'fecha_inscripcion' => now(),
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Inscripciones realizadas correctamente.',
            'niveles_inscritos' => $niveles
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Error al registrar inscripciones.',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
