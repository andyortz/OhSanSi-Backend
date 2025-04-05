<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstructuraOlimpiadaController extends Controller
{
    /**
     * Obtiene las áreas, niveles y grados asociados a una olimpiada.
     *
     * @param  int  $id_olimpiada
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerEstructuraOlimpiada($id_olimpiada)
    {
        $estructura = DB::table('niveles_areas_olimpiadas as nao')
            ->join('areas_competencia as a', 'nao.id_area', '=', 'a.id_area')
            ->join('niveles_categoria as nc', 'nao.id_nivel', '=', 'nc.id_nivel')
            ->leftJoin('grados_niveles as gn', 'nc.id_nivel', '=', 'gn.id_nivel')
            ->leftJoin('grados as g', 'gn.id_grado', '=', 'g.id_grado')
            ->where('nao.id_olimpiada', $id_olimpiada)
            ->select(
                'a.id_area',
                'a.nombre as nombre_area',
                'nc.id_nivel',
                'nc.nombre as nombre_nivel',
                'g.id_grado',
                'g.nombre_grado'
            )
            ->get()
            ->groupBy('id_area')
            ->map(function ($items, $id_area) {
                $nombre_area = $items->first()->nombre_area;

                $nivelesAgrupados = $items->groupBy('id_nivel')->map(function ($niveles) {
                    return [
                        'id_nivel' => $niveles->first()->id_nivel,
                        'nombre_nivel' => $niveles->first()->nombre_nivel,
                        'grados' => $niveles->filter(fn($n) => $n->id_grado)->map(function ($grado) {
                            return [
                                'id_grado' => $grado->id_grado,
                                'nombre_grado' => $grado->nombre_grado
                            ];
                        })->unique('id_grado')->values()
                    ];
                })->values();

                return [
                    'id_area' => $id_area,
                    'nombre_area' => $nombre_area,
                    'niveles' => $nivelesAgrupados
                ];
            })->values();

        return response()->json([
            'message' => 'Estructura de olimpiada cargada correctamente.',
            'estructura' => $estructura
        ], 200);
    }
}
