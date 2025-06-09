<?php

namespace App\Modules\Olympiad\Controllers;
use App\Modules\Olympiad\Models\Grade;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    public function index()
    {
        $grades = Grado::all();
        return response()->json($grades, 200);
    }
}
