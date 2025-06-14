<?php

namespace App\Modules\Persons\Controllers;

use App\Modules\Persons\Models\Person;
use App\Services\Registers\PersonService;
use App\Modules\Persons\Requests\StorePersonRequest;
use Illuminate\Http\Request;

class TutorController
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
        $tutor = Persona::where('correo_electronico', $email)->first();

        return $tutor
            ? response()->json($tutor)
            : response()->json(['message' => 'No encontrado'], 404);
    }

    public function store(StorePersonRequest $request)
    {
        try {
            $validated = $request->validated();

            $person = PersonService::register($validated);

            return response()->json([
                'message' => 'Tutor registrado exitosamente',
                'tutor' => $person,
                'status' => 201
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al registrar tutor',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
