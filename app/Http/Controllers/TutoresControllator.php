<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTutorRequest;
use App\models\Tutor;

class TutoresControllator extends Controller
{
    //
    public function store(Request $request)
    {

        $tutorExiste = Tutor::where('ci', $request->ci)
            ->orWhere('correo_electronico', $request->correo_electronico)
            ->first();
        
        // Validar los datos de entrada
        if ($tutorExiste) {
            $data = [
                'message' => 'El tutor ya está registrado en el sistema',
                'tutor_existente' => $tutorExiste,
                'status' => 409 // Conflict
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
}
