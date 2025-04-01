<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInscripcionRequest;
use App\Models\Inscripcion;
use App\Models\Pagos;
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
    
            // Crear un pago dummy temporal
            $pago = Pagos::create([
                'id_olimpiada'    => $request->id_olimpiada,
                'comprobante'     => 'registro_automático',
                'fecha_pago'      => now(),
                'nombre_pagador'  => 'Registro sin pago',
                'monto_pagado'    => 0,
                'verificado'      => false,
                'verificado_en'   => null,
                'verificado_por'  => null,
            ]);
    
            // Registrar cada nivel con ese ID de pago
            foreach ($request->niveles as $id_nivel) {
                Inscripcion::create([
                    'id_olimpiada' => $request->id_olimpiada,
                    'id_olimpista' => $request->id_olimpista,
                    'id_pago' => $pago->id_pago, // aquí va el nuevo id
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
