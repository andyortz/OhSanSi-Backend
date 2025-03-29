<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class AreasController extends Controller
{
    //

    public function index()
    {
        $adreas = Area::all();
        return response()-> json($adreas,200);
    }

    public function store(Request $request)
    {
        // $validator =Validator::make($request->all(),[
        //     'id_olimpiada' => 'required|integer|exists:olimpiadas,id_olimpiada',
        //     'nombre' => 'required|string|max:50',
        //     'imagen' => 'required|image|max:2048'
        // ]);

        // if($validator -> fails())
        // {
        //     $data=[
        //         'message' => 'Error al subir datos',
        //         'errors' =>$validator->errors(),
        //         'status' => 400
        //     ];
        //     return response() ->json($data,400);
        // }
        $imagePath = $request->file('imagen')->store('areas', 'public');
        
        $areaExiste =DB::table('areas_competencia')
            ->where('nombre', $request->nombre)
            ->where('id_olimpiada',$request -> id_olimpiada)
            ->first();
        if(!$areaExiste){
            $area = Area::create([
                'id_olimpiada' => $request->id_olimpiada,
                'nombre' => $request->nombre,
                'imagen' => $imagePath
            ]);   
            if (!$area) {
                $data = [
                    'message' => 'Error al crear el area',
                    'status' => 500
                ];
                return response()->json($data, 500);
            } 
            $data = [
                'area' => $area,
                'status' => 201
            ];
            
        }else{
            $data=[
                'message'=>'El Area ha sido registrada con anterioridad',
                'status' =>201
            ];
        }


        return response()->json($data, 201);
    }
}
