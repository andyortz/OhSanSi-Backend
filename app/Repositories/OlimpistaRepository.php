<?php

namespace App\Repositories;

use App\Models\Olimpista;
use App\Models\Grado;
use App\Models\Area;
use App\Models\NivelAreaOlimpiada;
use App\Models\NivelCategoria;
use App\Models\NivelGrado;

class OlimpistaRepository
{
    public function getAreasNiveles($ci)
    {
        // 1. Obtener el grado del olimpista
        $olimpista = Olimpista::select('id_grado')
        ->where('cedula_identidad', $ci)
        ->firstOrFail();

        // 2. Obtener niveles asociados al grado
        $niveles = NivelGrado::with(['nivel', 'nivelAreas.area'])
        ->where('id_grado', $olimpista->id_grado)
        ->get();
        // 3. Procesar los datos para la respuesta
        $resultado = Area::with(['asociaciones.nivelGrado.nivel'])
        ->whereHas('asociaciones.nivelGrado')
        ->get()
        ->map(function($area) {
            return [
                'id_area' => $area->id_area,
                'nombre_area' => $area->nombre,
                'niveles' => $area->asociaciones
                    ->flatMap(function($nivelArea) {
                        // Asegurarse que nivelGrado es una colección
                        return collect([$nivelArea->nivelGrado])
                            ->filter()
                            ->map(function($nivelGrado) {
                                return [
                                    'id_nivel' => $nivelGrado->id_nivel,
                                    'nombre_nivel' => $nivelGrado->nivel->nombre,
                                    'id_grado' => $nivelGrado->id_grado
                                ];
                            });
                    })
                    ->unique('id_nivel')
                    ->values()
            ];
        });

        return $resultado;
    }
}