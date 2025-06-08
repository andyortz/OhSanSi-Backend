<?php

namespace App\Http\Controllers;

use App\Modules\Models\Olympiad\Olympiad;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OlympiadYearController extends Controller
{

     /**
     * Devuelve todas las olimpiadas registradas en la base de datos.
     */
    public function index()
    {
        $olympiads = Olympiad::all();
        return response()->json($olympiads, 200);
    }
    public function index2()
    {
        $today = Carbon::now();
        $olympiads = Olympiad::where('start_date', '>', $today)->get();
        return response()->json($olympiads, 200);
    }
    /**
     * Retorna los datos de la olimpiada correspondiente a una gestión (año).
     *
     * @param  int|string  $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($year)
    {
        $olympiad = Olympiad::where('year', $year)->first();

        if (!$olympiad) {
            return response()->json([
                'message' => "No se encontró una olimpiada para la gestión $year."
            ], 404);
        }

        return response()->json($olympiad, 200);
    }
}
