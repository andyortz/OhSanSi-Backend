<?php

namespace App\Http\Controllers;

use App\Models\Olimpiada;
use Illuminate\Http\Request;

class OlympiadRegistrationController extends Controller
{
    public function index()
    {
        $olimpiadas = Olimpiada::all();
        return response()->json($olimpiadas, 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'gestion' => 'required|integer',
                'costo' => 'required|numeric',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date',
                'max_categorias_olimpista' => 'required|integer',
                'nombre_olimpiada' => 'nullable|string|max:255', // este campo existe en la tabla
            ]);

            $olimpiada = Olimpiada::create($validated);

            return response()->json([
                'message' => 'Olimpiada creada exitosamente',
                'olimpiada' => $olimpiada
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la olimpiada',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
