<?php

namespace App\Http\Controllers;

use App\Models\Provincia;

class ProvinciaController extends Controller
{
     public function index()
    {
        $provincias = Provincia::pluck('nombre_provincia');
        return response()->json($provincias, 200);
    }

    public function porDepartamento($id)
    {
        $provincias = Provincia::where('id_departamento', $id)->get();

        if ($provincias->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron provincias para este departamento.',
                'status' => 404
            ], 404);
        }

        return response()->json($provincias, 200);
    }
}
