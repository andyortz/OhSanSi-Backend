<?php

namespace App\Http\Controllers;

use App\Modules\Olympist\Models\Person;
use App\Modules\Olympiad\Models\Olympiad;
use App\Modules\Olympist\Models\OlympicDetail;
use App\Modules\Olympist\Models\Enrollment;
use App\Modules\Olympiad\Models\AreaLevelOlympiad;;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    //enrollments
    public function getEnrollmentsByCi($ci)
    {
        try {
            // 1. Buscar el detalle olimpista
            $detail = OlympicDetail::where('ci_olympic', $ci)->first();

            if (!$detail) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el olimpista'
                ], 404);
            }

            // 2. Obtener la olimpiada actual
            $currentOlympiad = Olimpiad::whereDate('start_date', '<=', now())
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
                'category_level:id_level,name',
                'category_level.area_level_olympiad' => function($query) use ($currentOlympiad) {
                    $query->where('id_olympiad', $currentOlympiad->id_olympiad)
                        ->with('area:id_area,area');
                }
            ])
            ->where('id_olympic_detail', $detail->id_olympic_detail)
            ->get();

            // 4. Formatear la respuesta
            $response = [
                
                'enrollments' => $enrollments->map(function ($enrollment) {
                    // Filtrar solo asociaciones válidas (no null)
                    $validAssociation = $enrollment->level->area_level_olympiad->firstWhere('area', '!=', null);
                    
                    return [
                        'id_enrollment' => $enrollment->id_enrollment,
                        'level' => $enrollment->level ? [
                            'id_level' => $enrollment->level->id_level,
                            'name' => $enrollment->level->name
                        ] : null,
                        'area' => $validAssociation ? [
                            'id_area' => $validAssociation->area->id_area,
                            'name' => $validAssociation->area->name
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
                'message' => 'failed fetching enrollments',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
