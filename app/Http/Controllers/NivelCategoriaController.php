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

    public function nivelesPorArea($id_area)
    {
        $niveles = NivelCategoria::where('id_area', $id_area)
            ->where('permite_seleccion_nivel', true)
            ->get();

        return response()->json([
            'permite_seleccionar_nivel' => $niveles->count() > 1,
            'niveles' => $niveles
        ], 200);
    }

    public function store(StoreNivelRequest $request)
    {
        // Calcular si el nivel es seleccionable (más de un grado)
        $permiteSeleccion = $request->grado_min != $request->grado_max;

        // Crear el nivel con ese valor
        $nivel = NivelCategoria::create([
            'nombre' => $request->nombre,
            'id_area' => $request->id_area,
            'permite_seleccion_nivel' => $permiteSeleccion
        ]);

        if (!$nivel) {
            return response()->json([
                'message' => 'Error al crear el nivel',
                'status' => 500
            ], 500);
        }

        // Buscar grados dentro del intervalo (por ID)
        $grados = Grado::whereBetween('id_grado', [$request->grado_min, $request->grado_max])->get();

        if ($grados->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron grados en el rango especificado',
                'status' => 400
            ], 400);
        }

        // Crear las relaciones nivel ↔ grado
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
