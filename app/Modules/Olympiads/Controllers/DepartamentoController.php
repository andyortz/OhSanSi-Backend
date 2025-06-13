<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Department;

class DepartamentoController
{
    public function index()
    {
        $departamentos = Departamento::all();
        return response()->json($departamentos, 200);
    }
}
