<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Olimpista;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InscripcionNivelesController extends Controller
{
    public function store(Request $request)
    {
        try {
            $ci = $request->input('ci');
            $niveles = $request->input('niveles'); // array de id_nivel

            if (!$ci || !is_array($niveles) || count($niveles) === 0) {
                return response()->json(['message' => 'CI y niveles son requeridos.'], 400);
            }

            $olimpista = Olimpista::where('cedula_identidad', $ci)->first();

            if (!$olimpista) {
                return response()->json(['message' => 'Olimpista no encontrado.'], 404);
            }

            DB::beginTransaction();

            foreach ($niveles as $nivel) {
                // Crear pago dummy
                $pago = Pagos::create([
                    'comprobante' => 'PAGO-DUMMY-' . uniqid(),
                    'fecha_pago' => now(),
                    'nombre_pagador' => $olimpista->nombres . ' ' . $olimpista->apellidos,
                    'monto_pagado' => 0,
                    'verificado' => false,
                ]);

                Inscripcion::create([
                    'id_olimpista' => $olimpista->id_olimpista,
                    'id_nivel' => $nivel,
                    'id_pago' => $pago->id_pago,
                    'fecha_inscripcion' => now(),
                    'estado' => 'PENDIENTE',
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Inscripciones registradas correctamente.'], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error interno al registrar.',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
