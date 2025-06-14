<?php

namespace App\Modules\Persons\Controllers;
//OJITO
use App\Modules\Persons\Models\Person;
use App\Services\Registers\PersonService;
use App\Modules\Persons\Requests\StoreTeacherRequest;
use Illuminate\Http\Request;

class ProfesorController
{
    public function buscarPorCi($ci)
    {
        $tutor = Person::where('person_ci', $ci)->first();

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
        $tutor = Person::where('email', $email)->first();

        return $tutor
            ? response()->json($tutor)
            : response()->json(['message' => 'No encontrado'], 404);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'names' => 'nullable|string|max:100',
                'surname' => 'nullable|string|max:100',
                'ci' => 'nullable|integer|unique:person,person_ci',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:100',
            ]);

            $persona = PersonService::register($validated);

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
