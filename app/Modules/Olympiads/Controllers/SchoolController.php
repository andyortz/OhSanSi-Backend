<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController
{
    public function index()
    {
        $schools = School::all();
        return response()->json($schools, 200);
    }
    public function byProvince($id)
    {
        $schools = School::where('province_id', $id)->get();
        if($schools->isEmpty()){
            return response()->kson([
                'message' =>'No se encontraron colegios para esta provincia.',
                'status' => 404 
            ]);
        }
        return response()->json($schools, 200);
    }

    public function onlyNames()
    {
        $names = School::select('school_id', 'school_name')->get();
        return response()->json($names, 200);
    }
}
