<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Department;

class DepartamentController
{
    public function index()
    {
        $departaments = Departament::all();
        return response()->json($departaments, 200);
    }
}
