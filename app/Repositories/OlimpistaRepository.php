<?php
namespace App\Repositories;



use App\Modules\Olympiad\Models\Area;
use App\Modules\Olympist\Models\OlympicDetail;
use App\Modules\Olympiad\Models\GradeLevel;
use App\Modules\Olympiad\Models\Olympiad;

class OlimpistaRepository
{
    public function getAreasNiveles($ci)
    {
        // 1. Obtener el grado del olimpista
        $olimpista = DetalleOlimpista::select('id_grado')
            ->where('ci_olimpista', $ci)
            ->firstOrFail();

        // 2. Obtener la olimpiada actual (basada en la fecha de hoy)
        $olimpiadaActual = Olimpiada::whereDate('fecha_inicio', '<=', now())
            ->whereDate('fecha_fin', '>=', now())
            ->first();

        if (!$olimpiadaActual) {
            return response()->json([
                'success' => false,
                'message' => 'No hay una olimpiada activa en la fecha actual'
            ], 404);
        }

        // 3. Consulta principal con todos los filtros
        $resultado = Area::whereHas('asociaciones', function($query) use ($olimpiadaActual, $olimpista) {
                $query->where('id_olimpiada', $olimpiadaActual->id_olimpiada)
                    ->whereHas('nivelGrado', function($q) use ($olimpista) {
                        $q->where('id_grado', $olimpista->id_grado);
                    });
            })
            ->with(['asociaciones' => function($query) use ($olimpista) {
                $query->whereHas('nivelGrado', function($q) use ($olimpista) {
                        $q->where('id_grado', $olimpista->id_grado);
                    })
                    ->with(['nivelGrado.nivel']);
            }])
            ->get()
            ->map(function($area) use ($olimpista) {
                $nivelesFiltrados = $area->asociaciones
                    ->filter(function($nivelArea) {
                        return $nivelArea->nivelGrado !== null;
                    })
                    ->map(function($nivelArea) {
                        return [
                            'id_nivel' => $nivelArea->nivelGrado->id_nivel,
                            'nombre_nivel' => $nivelArea->nivelGrado->nivel->nombre,
                            'id_grado' => $nivelArea->nivelGrado->id_grado
                        ];
                    })
                    ->unique('id_nivel')
                    ->values();

                return $nivelesFiltrados->isNotEmpty() ? [
                    'id_area' => $area->id_area,
                    'nombre_area' => $area->nombre,
                    'niveles' => $nivelesFiltrados
                ] : null;
            })
            ->filter()
            ->values();

        return $resultado;
    }
}