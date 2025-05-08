<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Services\Registers\PersonaService;
use App\Http\Requests\StorePersonaRequest;
use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    public function buscarPorCi($ci)
    {
        $tutor = Persona::where('ci_persona', $ci)->first();

        if ($tutor) {
            return response()->json([
                'message' => 'Tutor encontrado',
                'tutor' => $tutor,
                'status' => 200
            ], 200);
        }

        return response()->json([
            'message' => 'Tutor no encontrado',
            'status' => 404
        ], 404);
    }

    public function getByEmail($email)
    {
        $tutor = Persona::where('correo_electronico', $email)->first();

        return $tutor
            ? response()->json($tutor)
            : response()->json(['message' => 'No encontrado'], 404);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombres' => 'nullable|string|max:100',
                'apellidos' => 'nullable|string|max:100',
                'ci' => 'nullable|integer|unique:persona,ci_persona',
                'celular' => 'nullable|string|max:20',
                'correo_electronico' => 'nullable|email|max:100',
            ]);

            $persona = PersonaService::register($validated);

            return response()->json([
                'message' => 'Profesor registrado exitosamente',
                'tutor' => $persona,
                'status' => 201
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al registrar profesor',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
