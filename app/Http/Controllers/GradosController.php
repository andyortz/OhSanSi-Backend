<?php

namespace App\Http\Controllers;
use App\Models\Grado;
use Illuminate\Http\Request;

class GradosController extends Controller
{
    public function index()
    {
        $grados = Grado::all();
        return response()->json($grados, 200);
    }
}
