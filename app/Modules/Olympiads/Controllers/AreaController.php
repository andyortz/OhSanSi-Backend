<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Area;
use App\Modules\Olympiads\Requests\StoreAreaRequest;
use Illuminate\Http\Request\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Olympiads\Models\OlympiadAreaLevel;

class AreaController
{
    /**
     * Obtener todas las áreas
     */
    public function index()
    {
        $areas = Area::all();
        return response()->json($areas, 200);
    }

    /**
     * Obtener áreas por ID de olimpiada
     */
    public function areasByOlympiad($id)
    {
        $areas = NivelAreaOlimpiada::where('olympiad_id', $id)
            ->join('area', 'olympiad_area_level.area_id', '=', 'area.area_id')
            ->select('area.area_id', 'area.name')
            ->groupBy('area.area_id', 'area.name')
            ->get();

        if ($areas->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron áreas para la olimpiada especificada.',
                'areas' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Áreas encontradas exitosamente.',
            'areas' => $areas
        ], 200);
    }

    /**
     * Registrar una nueva área
     */
    public function store(StoreAreaRequest $request)
    {
        Area::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Área registrada exitosamente',
            'status' => 201
        ], 201);
    }

}
