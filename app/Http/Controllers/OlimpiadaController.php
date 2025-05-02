<?php

namespace App\Http\Controllers;

use App\Models\Olimpiada;
use App\Models\NivelAreaOlimpiada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OlimpiadaController extends Controller
{
    /**
     * Devuelve la olimpiada activa (si existe) comparando fechas con la actual.
     */
    public function verificarOlimpiadaAbierta()
    {
        $hoy = Carbon::now()->toDateString();

        $olimpiada = Olimpiada::where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->first();

        if ($olimpiada) {
            return response()->json([
                'abierta' => true,
                'olimpiada' => $olimpiada
            ], 200);
        } else {
            return response()->json([
                'abierta' => false,
                'message' => 'No hay olimpiadas activas en este momento.'
            ], 404);
        }
    }
    public function getMaxCategorias(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $fecha = $request->input('fecha');

        $olimpiada = Olimpiada::where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha)
            ->first();

        if (!$olimpiada) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una olimpiada activa en esa fecha.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'fecha' => $fecha,
            'id_olimpiada' => $olimpiada->id_olimpiada,
            'max_categorias_olimpista' => $olimpiada->max_categorias_olimpista
        ]);
    }
    public function getAreasConNiveles($idOlimpiada)
    {
        \Log::info('Iniciando getAreasConNiveles', ['idOlimpiada' => $idOlimpiada]);
        try {
            // Obtener la olimpiada con su gestión
            $olimpiada = Olimpiada::findOrFail($idOlimpiada);
            
            // Obtener todas las relaciones área-nivel para esta olimpiada
            $areasConNiveles = NivelAreaOlimpiada::with([
                'area:id_area,nombre',
                'nivel:id_nivel,nombre'
            ])
            ->where('id_olimpiada', $idOlimpiada)
            ->get()
            ->groupBy('id_area'); // Agrupar por área

            // Formatear la respuesta
            $response = [
                'gestion' => $olimpiada->gestion, // Asume que tienes este campo
                'areas' => $areasConNiveles->map(function ($items, $idArea) {
                    return [
                        'id_area' => $idArea,
                        'nombre_area' => $items->first()->area->nombre,
                        'niveles' => $items->map(function ($item) {
                            return [
                                'id_nivel' => $item->id_nivel,
                                'nombre_nivel' => $item->nivel->nombre
                            ];
                        })->unique('id_nivel')->values() // Eliminar duplicados
                    ];
                })->values()
            ];

            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en getAreasConNiveles', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

}
