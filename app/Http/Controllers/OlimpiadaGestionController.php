<?php

namespace App\Http\Controllers;

use App\Models\Olimpiada;
use Illuminate\Http\Request;

class OlimpiadaGestionController extends Controller
{
    /**
     * Retorna los datos de la olimpiada correspondiente a una gestión (año).
     *
     * @param  int|string  $gestion
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($gestion)
    {
        $olimpiada = Olimpiada::where('gestion', $gestion)->first();

        if (!$olimpiada) {
            return response()->json([
                'message' => "No se encontró una olimpiada para la gestión $gestion."
            ], 404);
        }

        return response()->json($olimpiada, 200);
    }
}
