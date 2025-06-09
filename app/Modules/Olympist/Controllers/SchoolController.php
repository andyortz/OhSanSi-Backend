<?php

namespace App\Modules\Olympist\Controllers;


use App\Modules\Olympist\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    //Obtain all schools
    public function index()
    {
        $schools = School::all();
        return response()->json($schools, 200);
    }
    public function byProvince($id)
    {
        $schools = School::where('id_province', $id)->get();
        if($schools->isEmpty()){
            return response()->kson([
                'message' =>'No schools were found for this province.',
                'status' => 404 
            ]);
        }
        return response()->json($schools, 200);
    }

    public function onlyNames()
    {
        $names = School::select('id_school', 'school_name')->get();
        return response()->json($names, 200);
    }
}
