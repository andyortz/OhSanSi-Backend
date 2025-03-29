<?php

namespace App\Http\Controllers;

use App\Models\NivelCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NivelCategoriaController extends Controller
{
    public function index()
    {
        $niveles = NivelCategoria::all();
        return response()->json($niveles, 200);
    }

    public function store(Request $request)
    {
        // Validaciones con Validator
        $validator = Validator::make($request->all(), [
            'nombre'   => 'required|string|max:50',
            'codigo'   => 'required|string|max:10',
            'id_area'  => 'required|integer|exists:areas_competencia,id_area'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error al subir datos',
                'errors'  => $validator->errors(),
                'status'  => 400
            ];
            return response()->json($data, 400);
        }

        // Crear el nuevo nivel
        $nivel = NivelCategoria::create([
            'nombre'  => $request->nombre,
            'codigo'  => $request->codigo,
            'id_area' => $request->id_area
        ]);

        if (!$nivel) {
            $data = [
                'message' => 'Error al crear el nivel',
                'status'  => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'nivel'  => $nivel,
            'status' => 201
        ];
        return response()->json($data, 201);
    }
}
