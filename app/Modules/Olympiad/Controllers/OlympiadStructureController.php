<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OlympiadStructureController extends Controller
{
    /**
     * Obtiene las áreas, niveles y grados asociados a una olimpiada.
     *
     * @param  int  $id_olympiad
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStructureOlympiad($id_olympiad)
    {
        $structure = DB::table('area_level_olympiad as nao')
            ->join('area as a', 'nao.id_area', '=', 'a.id_area')
            ->join('category_level as nc', 'nao.id_level', '=', 'nc.id_level')
            ->leftJoin('grade_level as gn', 'nc.id_level', '=', 'gn.id_level')
            ->leftJoin('grade as g', 'gn.id_grade', '=', 'g.id_grade')
            ->where('nao.id_olympiad', $id_olympiad)
            ->select(
                'a.id_area',
                'a.name as area_name',
                'nc.id_level',
                'nc.name as level_name',
                'g.id_grade',
                'g.grade_name'
            )
            ->get()
            ->groupBy('id_area')
            ->map(function ($items, $id_area) {
                $area_name = $items->first()->area_name;

                $levelsAgrupated = $items->groupBy('id_level')->map(function ($levels) {
                    return [
                        'id_level' => $levels->first()->id_level,
                        'level_name' => $levels->first()->level_name,
                        'grades' => $levels->filter(fn($n) => $n->id_grade)->map(function ($grade) {
                            return [
                                'id_grade' => $grade->id_grado,
                                'grade_name' => $grade->grade_name
                            ];
                        })->unique('id_grade')->values()
                    ];
                })->values();

                return [
                    'id_area' => $id_area,
                    'area_name' => $area_name,
                    'levels' => $levelsAgrupated
                ];
            })->values();

        return response()->json([
            'message' => 'Estructura de olimpiada cargada correctamente.',
            'structure' => $structure
        ], 200);
    }
}
