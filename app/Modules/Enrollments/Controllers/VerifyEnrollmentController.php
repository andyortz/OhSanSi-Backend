<?php

namespace App\Modules\Enrollments\Controllers;

use App\Modules\Persons\Models\Person;
use App\Modules\Olympiads\Models\Olympiad;
use App\Modules\Persons\Models\OlympistDetail;
use App\Modules\Enrollments\Models\Enrollment;
use App\Models\NivelAreaOlimpiada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerifyEnrollmentController
{
    public function verificar(Request $request)
    {
        try {
            $ciTutor = $request->input('ci_tutor');
            $ciOlimpista = $request->input('ci_olimpista');

            if (!$ciTutor || !$ciOlimpista) {
                return response()->json(['message' => 'Debe enviar CI del tutor y del olimpista.'], 400);
            }

            $tutor = Tutor::where('ci', $ciTutor)->first();
            $olimpista = Olimpista::where('cedula_identidad', $ciOlimpista)->first();

            if (!$tutor || !$olimpista) {
                return response()->json(['message' => 'Tutor u Olimpista no encontrados.'], 404);
            }

            Parentesco::firstOrCreate([
                'id_olimpista' => $olimpista->id_olimpista,
                'id_tutor' => $tutor->id_tutor
            ]);

            $fechaActual = Carbon::now()->toDateString();
            $olimpiada = Olimpiada::where('fecha_inicio', '<=', $fechaActual)
                ->where('fecha_fin', '>=', $fechaActual)
                ->first();

            if (!$olimpiada) {
                return response()->json(['message' => 'No hay una olimpiada activa.'], 404);
            }

            // Obtener inscripciones del olimpista
            $inscripciones = Inscripcion::where('id_olimpista', $olimpista->id_olimpista)->get();
            $nivelesInscritos = $inscripciones->pluck('id_nivel');

            if ($inscripciones->count() >= $olimpiada->max_categorias_olimpista) {
                return response()->json(['message' => 'El olimpista ya alcanzó el máximo de inscripciones permitidas.'], 403);
            }

            // Obtener todas las combinaciones nivel/área/olimpiada
            $relaciones = NivelAreaOlimpiada::with(['area', 'nivel'])
                ->where('id_olimpiada', $olimpiada->id_olimpiada)
                ->get();

            $contadorPorArea = [];

            // Contar inscripciones por área actual
            foreach ($relaciones as $rel) {
                if ($nivelesInscritos->contains($rel->id_nivel)) {
                    $contadorPorArea[$rel->id_area] = ($contadorPorArea[$rel->id_area] ?? 0) + 1;
                }
            }

            $disponibles = [];

            foreach ($relaciones as $rel) {
                $areaId = $rel->id_area;
                $nivelId = $rel->id_nivel;

                $yaInscritos = $contadorPorArea[$areaId] ?? 0;

                if ($yaInscritos < $rel->max_niveles && !$nivelesInscritos->contains($nivelId)) {
                    $disponibles[$rel->area->nombre][] = [
                        'id_nivel' => $nivelId,
                        'nombre_nivel' => $rel->nivel->nombre
                    ];
                }
            }

            return response()->json([
                'areas_disponibles' => $disponibles
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error interno',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    
    public function getTotalInscripciones($ci)
    {
        // Buscar al olimpista por CI
        $olimpista = Olimpista::where('cedula_identidad', $ci)->first();

        if (!$olimpista) {
            return response()->json([
                'success' => false,
                'message' => 'Olimpista no encontrado'
            ], 404);
        }

        // Contar inscripciones por id_olimpista
        $total = Inscripcion::where('id_olimpista', $olimpista->id_olimpista)->count();

        return response()->json([
            'success' => true,
            'ci_olimpista' => $ci,
            'total_inscripciones' => $total
        ]);
    }
    
    public function getEnrollmentsByCI($ci)
    {
        try {
            // 1. Buscar el detalle olimpista
            $detail = OlympistDetail::where('olympist_ci', $ci)->first();

            if (!$detail) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el olimpista'
                ], 404);
            }

            // 2. Obtener la olimpiada actual
            $currentOlympiad = Olympiad::whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if (!$currentOlympiad) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay una olimpiada activa actualmente'
                ], 404);
            }

            // 3. Obtener inscripciones con relaciones necesarias
            $enrollments = Enrollment::with([
                'level:level_id,level_name',
                'level.olympiadAreaLevel' => function($query) use ($currentOlympiad) {
                    $query->where('olympiad_id', $currentOlympiad->olympiad_id)
                        ->with('area:area_id,area_name');
                }
            ])
            ->where('olympist_detail_id', $detail->olympist_detail_id)
            ->get();

            // 4. Formatear la respuesta
            $response = [
                
                'enrollments' => $enrollments->map(function ($enrollment) {
                    // Filtrar solo asociaciones válidas (no null)
                    $validAssociation = $enrollment->level->olympiadAreaLevel->firstWhere('area', '!=', null);
                    
                    return [
                        'enrollment_id' => $enrollment->enrollment_id,
                        'level' => $enrollment->level ? [
                            'level_id' => $enrollment->level->level_id,
                            'level_name' => $enrollment->level->level_name
                        ] : null,
                        'area' => $validAssociation ? [
                            'area_id' => $validAssociation->area->area_id,
                            'area_name' => $validAssociation->area->area_name
                        ] : null
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener inscripciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
