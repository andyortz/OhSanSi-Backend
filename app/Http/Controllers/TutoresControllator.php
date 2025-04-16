<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTutorRequest;
use App\models\Tutor;

class TutoresControllator extends Controller
{
    //
    // Método para verificar si un tutor existe por CI
    public function buscarCi($request)
    {

        $tutor = Tutor::where('ci', $request)->first();

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
        $tutor = Tutor::where('correo_electronico', $email)->first();
    
        return $tutor
            ? response()->json($tutor)
            : response()->json(['message' => 'No encontrado'], 404);
    }
    public function store(Request $request)
{
    // Verificar si el tutor ya existe por CI antes de crearlo
    $tutorExistente = Tutor::where('ci', $request->ci)->first();
    if ($tutorExistente) {
        return response()->json([
            'message' => 'Error: Ya existe un tutor con este CI.',
            'id_tutor' => $tutorExistente->id_tutor, // Se agrega el ID del tutor existente
            'status' => 400
        ], 400);
    }

    // Si no existe, se crea el tutor
    $tutor = Tutor::create([
        'nombres' => $request->nombres,
        'apellidos' => $request->apellidos,
        'ci' => $request->ci,
        'celular' => $request->celular,
        'correo_electronico' => $request->correo_electronico,
        'rol_parentesco' => $request->rol_parentesco
    ]);

    if (!$tutor) {
        return response()->json([
            'message' => 'Error al crear el tutor',
            'status' => 500
        ], 500);
    }

    // Respuesta de éxito
    return response()->json([
        'message' => 'Tutor registrado exitosamente',
        'tutor' => $tutor,
        'status' => 201
    ], 201);
}

}
