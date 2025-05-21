<?php
namespace App\Repositories;

use App\Models\Area;
use App\Models\DetalleOlimpista;
use App\Models\NivelGrado;

class OlimpistaRepository
{
    public function getAreasNiveles($ci)
    {
        // 1. Obtener el grado del olimpista
        $olimpista = DetalleOlimpista::select('id_grado')
            ->where('ci_olimpista', $ci)
            ->firstOrFail();

        // 2. Obtener directamente las áreas con sus niveles filtrados por grado
        $resultado = Area::with(['asociaciones.nivelGrado' => function($query) use ($olimpista) {
                $query->where('id_grado', $olimpista->id_grado)
                      ->with('nivel'); // Cargar la relación nivel
            }])
            ->whereHas('asociaciones.nivelGrado', function($query) use ($olimpista) {
                $query->where('id_grado', $olimpista->id_grado);
            })
            ->get()
            ->map(function($area) use ($olimpista) {
                return [
                    'id_area' => $area->id_area,
                    'nombre_area' => $area->nombre,
                    'niveles' => $area->asociaciones
                        ->flatMap(function($nivelArea) use ($olimpista) {
                            return optional($nivelArea->nivelGrado)->id_grado == $olimpista->id_grado
                                ? [[
                                    'id_nivel' => $nivelArea->nivelGrado->id_nivel,
                                    'nombre_nivel' => $nivelArea->nivelGrado->nivel->nombre,
                                    'id_grado' => $nivelArea->nivelGrado->id_grado
                                ]]
                                : [];
                        })
                        ->unique('id_nivel')
                        ->values()
                ];
            });

        return $resultado;
    }
}