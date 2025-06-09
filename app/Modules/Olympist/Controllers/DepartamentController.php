<?php

namespace App\Modules\Olympist\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Olympist\Models\Departament;

class DepartamentController extends Controller
{
    public function index()
    {
        $departaments = Departament::all();
        return response()->json($departaments, 200);
    }
}
