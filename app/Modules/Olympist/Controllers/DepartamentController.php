<?php

namespace App\Http\Controllers;

use App\Modules\Olympist\Models\Departament;

class DepartamentController extends Controller
{
    public function index()
    {
        $departaments = Departament::all();
        return response()->json($departaments, 200);
    }
}
