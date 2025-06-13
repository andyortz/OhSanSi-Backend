<?php

namespace App\Modules\Olympiads\Controllers;
use App\Modules\Olympiads\Models\Grado;
use Illuminate\Http\Request;

class GradosController
{
    public function index()
    {
        $grados = Grado::all();
        return response()->json($grados, 200);
    }
}
