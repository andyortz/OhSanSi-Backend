<?php

namespace App\Modules\Enrollments\Controllers;

use App\Modules\Enrollments\Models\EnrollmentList;
use App\Modules\Persons\Models\Person;
use App\Modules\Enrollments\Models\Enrollment;
use App\Modules\Persons\Models\OlympistDetail;
use App\Modules\Enrollments\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LevelEnrollmentController
{
    public function storeOne(Request $request)
    {
        $data = $request->validate([
            'ci' => 'required|integer|exists:persona,ci_persona',
            'nivel' => 'required|integer',
            'id_pago' => 'nullable|integer',
            'estado' => 'nullable|string|max:50',
            'ci_tutor_academico' => 'nullable|numeric',
        ]);
        $ci_tutor_academico = $data['ci_tutor_academico'] ?? null;
        $nivel = $data['nivel'] ?? null;

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
            // // dd($data);  
            // dd($ci_tutor_academico);
            Inscripcion::create([
                'id_olimpiada' => $detalleOlimpista->id_olimpiada,
                'id_detalle_olimpista' => $detalleOlimpista->id_detalle_olimpista,
                'ci_tutor_academico' => $ci_tutor_academico, // <- CORRECTO
                'id_pago' => $idPago,
                'id_nivel' => $nivel,
                'estado' => strtoupper($estado),
                'fecha_inscripcion' => now(),
            ]);
            

            DB::commit();

            return response()->json([
                'message' => 'Inscripciones registradas correctamente.',
                'data' => $data,
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'ci' => 'required|integer|exists:persona,ci_persona',
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
        
        // Validación básica
        $request->validate([
            'ci' => 'required|exists:person,person_ci',
            'levels' => 'required|array|min:1',
            'levels.*level_id' => 'integer|exists:category_level,level_id',
            'levels.*.ci_tutor_academico' => 'nullable|exists:person,person_ci',
            'responsible_ci' => 'required|exists:person,person_ci',
        ]);
        $responsible = Person::where('person_ci', $request->responsible_ci)
        ->first(['names', 'surnames', 'person_ci']);
        try {
            DB::beginTransaction();
            $olympist = OlympistDetail::firstOrCreate(
                ['olympist_ci' => $request->ci]
            );
            // Crear inscripciones para cada nivel
            $list = EnrollmentList::create([
                'olympiad_id' => $olympist -> olympiad_id,
                'enrollment_responsible_ci' => $request->responsible_ci,
                'status' => 'PENDIENTE',
                'list_creation_date' => now()
            ]);
            $enrollments = [];
            foreach ($request->levels as $dataLevel) {
                $enrollments[] = Enrollment::create([
                    'olympist_detail_id' => $olympist->olympist_detail_id,
                    'academic_tutor_ci' => $dataLevel['academic_tutor_ci'] ?? null,
                    'list_id' => $list->list_id,
                    'level_id' => $dataLevel['level_id'],
                ]);
            }

        DB::commit();
        return response()->json([
            'message' => 'Inscripciones registradas correctamente.',
            'count' => count($enrollments),
            'responsible_id' => $request->responsible_ci,
            'names' => $responsible->names,
            'surnames' => $responsible->surnames,
            'data' => $enrollments
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
