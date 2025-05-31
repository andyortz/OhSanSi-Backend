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
    public function porProvincia($id)
    {
        $colegios = Colegio::where('id_provincia', $id)->get();
        if($colegios->isEmpty()){
            return response()->kson([
                'message' =>'No se encontraron colegios para esta provincia.',
                'status' => 404 
            ]);
        }
        return response()->json($colegios, 200);
    }

    public function soloNombres()
    {
        $nombres = Colegio::pluck('nombre_colegio');
        return response()->json($nombres, 200);
    }
}
