<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTutorRequest;
use App\models\Tutor;

class TutoresControllator extends Controller
{
    //
    // Método para verificar si un tutor existe por CI
    public function buscarCi(Request $request)
    {
        // Validar que se envíe el parámetro CI
        $request->validate([
            'ci' => 'required|numeric'
        ]);

        $tutor = Tutor::where('ci', $request->ci)->first();

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

    public function store(Request $request)
    {

        $tutorExiste = Tutor::where('ci', $request->ci)->first();
        
        $tutorConCorreo = Tutor::where('correo_electronico', $request->correo_electronico)->first();
        if ($tutorConCorreo) {
            return response()->json([
                'message' => 'El correo electrónico ya está registrado en el sistema',
                'tutor_existente' => $tutorConCorreo,
                'status' => 409
            ], 409);
        }
        
        if ($tutorExiste) {
            $data = [
                'message' => 'El tutor ya está registrado en el sistema',
                'tutor_existente' => $tutorExiste,
                'status' => 409
            ];
            return response()->json($data, 409);
        }else{
        
            $tutor = Tutor::create([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'celular' => $request->celular,
                'correo_electronico' => $request->correo_electronico,
                'rol_parentesco' => $request->rol_parentesco
            ]);
        }
        

        if (!$tutor) {
            $data = [
                'message' => 'Error al crear el tutor',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        // Respuesta de éxito
        $data = [
            'message' => 'Tutor registrado exitosamente',
            'tutor' => $tutor,
            'status' => 201 // Created
        ];
        return response()->json($data, 201);
    }
}
