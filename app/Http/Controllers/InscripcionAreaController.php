<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInscripcionRequest;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;

class InscripcionAreaController extends Controller
{
    /**
     * Registra al olimpista en uno o más niveles de competencia.
     */
    public function store(StoreInscripcionRequest $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->niveles as $id_nivel) {
                Inscripcion::create([
                    'id_olimpiada' => $request->id_olimpiada,
                    'id_olimpista' => $request->id_olimpista,
                    'id_pago' => $request->id_pago, // puede ser null
                    'id_nivel' => $id_nivel,
                    'estado' => $request->estado,
                    'fecha_inscripcion' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Inscripciones registradas correctamente.',
                'niveles_inscritos' => $request->niveles
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar la inscripción.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
