<?php

namespace App\Modules\Olympiads\Controllers;
use App\Modules\Olympiads\Models\Grade;
use Illuminate\Http\Request;

class GradeController
{
    public function index()
    {
        $grades = Grade::all();
        return response()->json($grades, 200);
    }
}
