<?php

namespace App\Http\Controllers;

use App\Models\NivelCategoria;
use App\Http\Requests\StoreNivelRequest;

class NivelCategoriaController extends Controller
{
    public function index()
    {
        $niveles = NivelCategoria::all();
        return response()->json($niveles, 200);
    }

    public function store(StoreNivelRequest $request) {
        $nivel = NivelCategoria::create($request->validated());

        if (!$nivel) {
            return response()->json([
                'message' => 'Error al crear el nivel',
                'status'  => 500
            ], 500);
        }

        return response()->json([
            'nivel'  => $nivel,
            'status' => 201
        ], 201);
    }
}
