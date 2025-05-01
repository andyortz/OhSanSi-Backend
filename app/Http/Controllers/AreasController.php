<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Http\Requests\StoreAreaRequest;
use Illuminate\Http\Request\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NivelAreaOlimpiada;

class AreasController extends Controller
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
    public function areasPorOlimpiada($id_olimpiada)
    {
        $areas = NivelAreaOlimpiada::where('id_olimpiada', $id_olimpiada)
            ->join('areas_competencia', 'niveles_areas_olimpiadas.id_area', '=', 'areas_competencia.id_area')
            ->select('areas_competencia.id_area', 'areas_competencia.nombre')
            ->groupBy('areas_competencia.id_area', 'areas_competencia.nombre')
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
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'message' => 'Área registrada exitosamente',
            'status' => 201
        ], 201);
    }

    public function areasConNivelesYGrados()
    {
        $areas = Area::with(['niveles.grados'])->get()->map(function ($area) {
            return [
                'id_area' => $area->id_area,
                'nombre_area' => $area->nombre,
                'niveles' => $area->niveles->map(function ($nivel) {
                    return [
                        'id_nivel' => $nivel->id_nivel,
                        'nombre_nivel' => $nivel->nombre,
                        'permite_seleccion_nivel' => $nivel->permite_seleccion_nivel,
                        'grados' => $nivel->grados->map(function ($grado) {
                            return [
                                'id_grado' => $grado->id_grado,
                                'nombre_grado' => $grado->nombre_grado
                            ];
                        })
                    ];
                })
            ];
        });

        return response()->json($areas, 200);
    }
}
