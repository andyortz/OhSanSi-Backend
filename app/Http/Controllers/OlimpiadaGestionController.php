<?php

namespace App\Http\Controllers;

use App\Models\Olimpiada;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OlimpiadaGestionController extends Controller
{

     /**
     * Devuelve todas las olimpiadas registradas en la base de datos.
     */
    public function index()
    {
        $olimpiadas = Olimpiada::all();
        return response()->json($olimpiadas, 200);
    }
    public function index2()
    {
        $hoy = Carbon::now();
        $olimpiadas = Olimpiada::where('fecha_inicio', '>', $hoy)->get();
        return response()->json($olimpiadas, 200);
    }
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
