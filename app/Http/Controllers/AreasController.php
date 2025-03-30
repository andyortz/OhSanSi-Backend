<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
     * Registrar una nueva área
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_olimpiada' => 'required|integer|exists:olimpiadas,id_olimpiada',
            'nombre' => 'required|string|max:50',
            'imagen' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al subir datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $imagePath = $request->file('imagen')->store('areas', 'public');

        $areaExiste = DB::table('areas_competencia')
            ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->where('id_olimpiada', $request->id_olimpiada)
            ->first();

        if ($areaExiste) {
            return response()->json([
                'data' => 'El área ya fue registrada anteriormente',
                'status' => 201
            ], 201);
        }

        $area = Area::create([
            'id_olimpiada' => $request->id_olimpiada,
            'nombre' => $request->nombre,
            'imagen' => $imagePath
        ]);

        if (!$area) {
            return response()->json([
                'message' => 'Error al crear el área',
                'status' => 500
            ], 500);
        }

        return response()->json([
            'area' => $area,
            'status' => 201
        ], 201);
    }
}
