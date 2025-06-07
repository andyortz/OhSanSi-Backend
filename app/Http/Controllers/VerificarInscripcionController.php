<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Olimpiada;
use App\Models\DetalleOlimpista;
use App\Models\Inscripcion;
use App\Models\NivelAreaOlimpiada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerificarInscripcionController extends Controller
{
    //enrollments
    public function getInscripcionesPorCI($ci)
    {
        try {
            // 1. Buscar el detalle olimpista
            $detalle = DetalleOlimpista::where('ci_olimpista', $ci)->first();

            if (!$detalle) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el olimpista'
                ], 404);
            }

            // 2. Obtener la olimpiada actual
            $olimpiadaActual = Olimpiada::whereDate('fecha_inicio', '<=', now())
                ->whereDate('fecha_fin', '>=', now())
                ->first();

            if (!$olimpiadaActual) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay una olimpiada activa actualmente'
                ], 404);
            }

            // 3. Obtener inscripciones con relaciones necesarias
            $inscripciones = Inscripcion::with([
                'nivel:id_nivel,nombre',
                'nivel.asociaciones' => function($query) use ($olimpiadaActual) {
                    $query->where('id_olimpiada', $olimpiadaActual->id_olimpiada)
                        ->with('area:id_area,nombre');
                }
            ])
            ->where('id_detalle_olimpista', $detalle->id_detalle_olimpista)
            ->get();

            // 4. Formatear la respuesta
            $response = [
                
                'inscripciones' => $inscripciones->map(function ($inscripcion) {
                    // Filtrar solo asociaciones válidas (no null)
                    $asociacionValida = $inscripcion->nivel->asociaciones->firstWhere('area', '!=', null);
                    
                    return [
                        'id_inscripcion' => $inscripcion->id_inscripcion,
                        'nivel' => $inscripcion->nivel ? [
                            'id_nivel' => $inscripcion->nivel->id_nivel,
                            'nombre' => $inscripcion->nivel->nombre
                        ] : null,
                        'area' => $asociacionValida ? [
                            'id_area' => $asociacionValida->area->id_area,
                            'nombre' => $asociacionValida->area->nombre
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
