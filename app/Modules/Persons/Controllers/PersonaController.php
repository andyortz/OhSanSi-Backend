<?php

namespace App\Modules\Persons\Controllers;

use App\Modules\Persons\Models\Persona;

class PersonaController
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
