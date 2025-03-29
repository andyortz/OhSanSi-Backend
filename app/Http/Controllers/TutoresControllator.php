<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutoresControllator extends Controller
{
    //
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nombres' => 'required|max:255',
            'apellidos' => 'required|max:255',
            'ci' => 'required|digits',
            'celular' => 'required|digits:10',
            'correo_electronico' => 'required|email|unique:tutor',
            'rol_parentesco' => 'required|in:English,Spanish,French'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $tutor = Tutor::create([
            'nombres' => $request ->nombres,
            'apellidos' => $request -> apellidos,
            'ci' => $request -> ci,
            'celular' => $request -> celular,
            'correo_electronico' => $request -> correo_electronico,
            'rol_parentesco' => $rquest -> rol_parenteszo
        ]);

        if (!$tutor) {
            $data = [
                'message' => 'Error al crear el tutor',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'tutor' => $tutor,
            'status' => 201
        ];

        return response()->json($data, 201);

    }
}
