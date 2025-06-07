<?php

namespace App\Http\Controllers;

use App\Modules\Olympist\Models\Province;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::select('id_province', 'province_name')->get();
        return response()->json($provinces, 200);
    }

    public function byDepartment($id)
    {
        $provinces = Province::where('id_department', $id)->get();

        if ($provinces->isEmpty()) {
            return response()->json([
                'message' => 'No provinces found for this department.',
                'status' => 404
            ], 404);
        }

        return response()->json($provinces, 200);
    }
}