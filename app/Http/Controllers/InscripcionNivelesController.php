<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Olimpista;
use App\Models\Inscripcion;
use App\Models\Tutor;
use App\Models\Parentesco;

use App\Http\Controllers\ParentescoController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
    public function storeWithTutor(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validación básica
            $request->validate([
                'ci' => 'required|exists:olimpistas,cedula_identidad',
                'niveles' => 'required|array|min:1',
                'ci_tutor' => 'nullable|exists:tutores,ci'
            ]);
    
            // 1. Obtener olimpista
            $olimpista = Olimpista::where('cedula_identidad', $request->ci)->first();
            if (!$olimpista) {
                throw new \Exception('Olimpista no encontrado');
            }
    
            $inscripcionRequest = new Request([
                'ci' => $request->ci,
                'niveles' => $request->niveles
            ]);

            $inscripcionResponse = app(InscripcionNivelesController::class)->store($inscripcionRequest);
            
            if ($inscripcionResponse->getStatusCode() !== 201) {
                $errorData = $inscripcionResponse->getData(true);
                throw new \Exception('Error en inscripción: ' . ($errorData['message'] ?? 'Sin mensaje'));
            }
    
            $responseData = [
                'inscripciones' => $inscripcionResponse->getData(true),
                'tutor_asociado' => false
            ];
    
            // 3. Procesar tutor si existe
            if ($request->ci_tutor) {
                $tutor = Tutor::where('ci', $request->ci_tutor)->firstOrFail();
                
                // Verificar si ya está asociado
                $yaAsociado = Parentesco::where('id_olimpista', $olimpista->id_olimpista)
                    ->where('id_tutor', $tutor->id_tutor)
                    ->exists();

                if (!$yaAsociado) {

                    $tutorRequest = new Request([
                        'id_olimpista' => $olimpista->id_olimpista,
                        'id_tutor' => $tutor->id_tutor
                    ]);
                    
                    // Llamar directamente al método que maneja la lógica
                    $tutorResponse = app(ParentescoController::class)->asociarTutor($tutorRequest);
                    
                    if ($tutorResponse->getStatusCode() !== 201) {
                        $errorData = $tutorResponse->getData(true);
                        throw new \Exception('Error en asociación tutor: ' . ($errorData['message'] ?? 'Sin mensaje'));
                    }
                }
                $responseData['tutor_asociado'] = true;
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Proceso completado',
                'data' => $responseData
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error en el proceso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
