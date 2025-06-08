<?php

namespace App\Http\Controllers;

use app\Modules\Olympiad\Models\Area;
use app\Modules\Olympiad\Requests\StoreAreaRequest;
use Illuminate\Http\Request\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\NivelAreaOlimpiada;
use App\Modules\Olympiad\Models\AreaLevelOlympiad;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        return response()->json($areas, 200);
    }

    public function areasByOlympiad($id_olympiad)
    {
        $areas = AreaLevelOlympiad::where('id_olympiad', $id_olympiad)
            ->join('area', 'area_level_olympiad.id_area', '=', 'area.id_area')
            ->select('area.id_area', 'area.name')
            ->groupBy('area.id_area', 'area.name')
            ->get();

        if ($areas->isEmpty()) {
            return response()->json([
                'message' => 'No areas were found for the specified Olympiad.',
                'areas' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Areas found successfully.',
            'areas' => $areas
        ], 200);
    }

    public function store(StoreAreaRequest $request)
    {
        Area::create([
            'name' => $request->nombre,
        ]);

        return response()->json([
            'message' => 'Successfully registered area',
            'status' => 201
        ], 201);
    }

    /**
     * Obtain all areas with their levels and grades  REVISAR!!!
     */
    // public function areasConNivelesYGrados()
    // {
    //     $areas = Area::with(['niveles.grados'])->get()->map(function ($area) {
    //         return [
    //             'id_area' => $area->id_area,
    //             'nombre_area' => $area->nombre,
    //             'niveles' => $area->niveles->map(function ($nivel) {
    //                 return [
    //                     'id_nivel' => $nivel->id_nivel,
    //                     'nombre_nivel' => $nivel->nombre,
    //                     'permite_seleccion_nivel' => $nivel->permite_seleccion_nivel,
    //                     'grados' => $nivel->grados->map(function ($grado) {
    //                         return [
    //                             'id_grado' => $grado->id_grado,
    //                             'nombre_grado' => $grado->nombre_grado
    //                         ];
    //                     })
    //                 ];
    //             })
    //         ];
    //     });

    //     return response()->json($areas, 200);
    // }
}
