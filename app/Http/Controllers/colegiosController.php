<?php

namespace App\Http\Controllers;

use App\Models\Colegio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class colegiosController extends Controller
{
    //Obtener todos los colegios
    public function index()
    {
        $colegios = Colegio::all();
        return response()->json($colegios, 200);
    }
}
