<?php

namespace App\Modules\Olympiads\Controllers;

use App\Models\Departamento;

class DepartamentoController
{
    public function index()
    {
        $departamentos = Departamento::all();
        return response()->json($departamentos, 200);
    }
}
