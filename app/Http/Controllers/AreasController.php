<?php

namespace App\Http\Controllers;
use App\Models\Area;

use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        return response()->json($areas, 200);
    }

    public function areasPorOlimpiada($id_olimpiada)
    {
        $areas = Area::where('id_olimpiada', $id_olimpiada)->get();

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
}
