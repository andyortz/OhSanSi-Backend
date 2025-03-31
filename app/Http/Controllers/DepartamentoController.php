<?php

namespace App\Http\Controllers;

use App\Models\Departamento;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::all();
        return response()->json($departamentos, 200);
    }
}
