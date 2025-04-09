<?php

namespace App\Http\Controllers;

use App\Models\Olimpiada;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OlimpiadaController extends Controller
{
    /**
     * Devuelve la olimpiada activa (si existe) comparando fechas con la actual.
     */
    public function verificarOlimpiadaAbierta()
    {
        $hoy = Carbon::now()->toDateString();

        $olimpiada = Olimpiada::where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->first();

        if ($olimpiada) {
            return response()->json([
                'abierta' => true,
                'olimpiada' => $olimpiada
            ], 200);
        } else {
            return response()->json([
                'abierta' => false,
                'message' => 'No hay olimpiadas activas en este momento.'
            ], 404);
        }
    }
}
