<?php

namespace App\Http\Controllers;
use App\Models\Area;

use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        return response()->json($areas, 200);
    }
}
