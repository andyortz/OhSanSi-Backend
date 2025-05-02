<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class TutoresControllator extends Controller
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
            $data = $request->validate([
                'nombres' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'ci' => 'required|integer|unique:personas,ci_persona',
                'celular' => 'nullable|string|max:20',
                'correo_electronico' => 'required|email|max:100|unique:personas,correo_electronico',
            ]);

            DB::beginTransaction();

            $tutor = new Persona();
            $tutor->ci_persona = $data['ci'];
            $tutor->nombres = $data['nombres'];
            $tutor->apellidos = $data['apellidos'];
            $tutor->correo_electronico = $data['correo_electronico'];
            $tutor->celular = $data['celular'] ?? null;
            $tutor->save();

            DB::commit();

            return response()->json([
                'message' => 'Tutor registrado exitosamente',
                'tutor' => $tutor,
                'status' => 201
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar tutor',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
