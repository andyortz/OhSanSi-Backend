<?php

namespace App\Http\Controllers;

use App\Models\NivelCategoria;
use App\Models\NivelGrado;
use App\Models\Grado;
use App\Http\Requests\StoreNivelRequest;

class NivelCategoriaController extends Controller
{
    public function index()
    {
        $niveles = NivelCategoria::all();
        return response()->json($niveles, 200);
    }

    public function store(StoreNivelRequest $request)
    {
        $nivel = NivelCategoria::create([
            'nombre' => $request->nombre,
            'id_area' => $request->id_area,
        ]);

        if (!$nivel) {
            return response()->json([
                'message' => 'Error al crear el nivel',
                'status' => 500
            ], 500);
        }

        // Rango de IDs directos
        $grados = Grado::whereBetween('id_grado', [$request->grado_min, $request->grado_max])->get();

        if ($grados->count() === 0) {
            return response()->json([
                'message' => 'No se encontraron grados en el rango especificado',
                'status' => 400
            ], 400);
        }

        foreach ($grados as $grado) {
            NivelGrado::create([
                'id_nivel' => $nivel->id_nivel,
                'id_grado' => $grado->id_grado
            ]);
        }

        return response()->json([
            'nivel' => $nivel,
            'asociaciones_grados' => $grados->pluck('id_grado'),
            'status' => 201
        ], 201);
    }

}
