<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Olimpista;
use App\Models\Inscripcion;
use App\Models\Tutor;
use App\Models\Parentesco;
use App\Models\DetalleOlimpista;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ParentescoController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InscripcionNivelesController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'ci' => 'required|integer|exists:personas,ci_persona',
            'niveles' => 'required|array|min:1',
            'id_pago' => 'nullable|integer',
            'estado' => 'nullable|string|max:50'
        ]);

        DB::beginTransaction();
        try {
            // Buscar detalle del olimpista
            $detalleOlimpista = DetalleOlimpista::where('ci_olimpista', $data['ci'])->first();

            if (!$detalleOlimpista) {
                return response()->json(['message' => 'Olimpista no encontrado en detalle_olimpistas.'], 404);
            }

            $estado = $data['estado'] ?? 'PENDIENTE';

            // Si no mandaron id_pago, crear un pago dummy
            $idPago = $data['id_pago'] ?? null;
            if (!$idPago) {
                $pago = Pago::create([
                    'comprobante' => 'PAGO-DUMMY-' . uniqid(),
                    'fecha_pago' => now(),
                    'ci_responsable_inscripcion' => $data['ci'],
                    'monto_pagado' => 0,
                    'verificado' => false,
                    'verificado_en' => now(),
                    'verificado_por' => null
                ]);
                $idPago = $pago->id_pago;
            }

            // Insertar inscripciones para cada nivel
            foreach ($data['niveles'] as $idNivel) {
                Inscripcion::create([
                    'id_olimpiada' => $detalleOlimpista->id_olimpiada,
                    'id_detalle_olimpista' => $detalleOlimpista->id_detalle_olimpista,
                    'ci_tutor_academico' => null, // Si quieres agregar después
                    'id_pago' => $idPago,
                    'id_nivel' => $idNivel,
                    'estado' => strtoupper($estado),
                    'fecha_inscripcion' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Inscripciones registradas correctamente.'
            ], 201);

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
                'ci_tutor' => 'nullable|exists:tutores,ci',
                'rol' => 'nullable|in:Tutor Academico,Tutor Legal'
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
                    
                    $rol = $request->input('rol', 'Tutor Academico');
                    $tutorRequest = new Request([
                        'id_olimpista' => $olimpista->id_olimpista,
                        'id_tutor' => $tutor->id_tutor,
                        'rol_parentesco' => $rol
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
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error en el proceso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function registrarVarios(Request $request)
    {
        $request->validate([
            'ci_tutor' => 'required|exists:tutores,ci',
            'olimpistas' => 'required|array|min:1',
            'olimpistas.*.ci_olimpista' => 'required|exists:olimpistas,cedula_identidad',
            'olimpistas.*.id_niveles' => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        try {
            $resultados = [];            
            foreach ($request->olimpistas as $data) {
                // 1. Crear inscripciones para las áreas dadas
                $inscripcionRequest = new Request([
                    'ci' => $data['ci_olimpista'],
                    'niveles' => $data['id_niveles'],
                    'ci_tutor' => $request->ci_tutor
                ]);
                $inscripcionResponse = app(InscripcionNivelesController::class)->storeWithTutor($inscripcionRequest);
                
                if ($inscripcionResponse->getStatusCode() !== 201) {

                    $errorData = $inscripcionResponse->getData(true);
                    throw new \Exception('Error en olimpista: ' . ($errorData['message'] ?? 'Sin mensaje'));
                }
                $resultados[] = [
                    'ci_olimpista' => $data['ci_olimpista'],
                    'response' => $inscripcionResponse,
                    'status' => 'ok'
                ];
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Todos los registros fueron realizados exitosamente.',
                'resultados' => $resultados
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([                
                'success' => false,
                'message' => 'Ocurrió un error durante el proceso, no se registró ningún olimpista.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function registrarMultiplesConTutor(Request $request)
    {
        $data = $request->validate([
            'olimpistas' => 'required|array|min:1',
            'olimpistas.*.ci_olimpista' => 'required|exists:olimpistas,cedula_identidad',
            'olimpistas.*.id_niveles' => 'required|array|min:1',
            'olimpistas.*.ci_tutor' => 'required|exists:tutores,ci',
            'olimpistas.*.rol' => 'required|in:Tutor Academico,Tutor Legal',
        ]);

        $resultados = [];

        DB::beginTransaction();

        try {
            foreach ($data['olimpistas'] as $olimpistaData) {
                // Obtener modelos
                $inscripcionRequest = new Request([
                    'ci' => $olimpistaData['ci_olimpista'],
                    'niveles' => $olimpistaData['id_niveles'],
                    'ci_tutor' => $olimpistaData['ci_tutor'],
                    'rol' => $olimpistaData['rol'],
                ]);
                $inscripcionResponse = app(InscripcionNivelesController::class)->storeWithTutor($inscripcionRequest);
                
                if ($inscripcionResponse->getStatusCode() !== 201) {
                    $errorData = $inscripcionResponse->getData(true);
                    throw new \Exception("Error inscribiendo olimpista {$olimpistaData['ci_olimpista']}: " . ($errorData['message'] ?? 'Sin mensaje'));
                }
                $resultados[] = [
                    'ci_olimpista' => $olimpistaData['ci_olimpista'],
                    'response' => $inscripcionResponse,
                    'status' => 'ok',
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Todos los olimpistas fueron registrados correctamente.',
                'data' => $resultados
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar registros. Se revirtió la operación.',
                'error' => $e->getMessage(),
                'procesados' => $resultados
            ], 500);
        }
    }
}
