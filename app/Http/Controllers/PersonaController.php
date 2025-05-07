<?php

namespace App\Http\Controllers;

use App\Models\Persona;

class PersonaController extends Controller
{
    public function getByCi($ci)
    {
        $persona = Persona::where('ci_persona', $ci)->get();

        if ($persona->isEmpty()) {
            return response()->json([
                'message' => 'La persona no esta registrada.',
                'ci' => $ci,
                'status' => 404
            ], 404);
        }

        return response()->json($persona, 200);
    }
}
