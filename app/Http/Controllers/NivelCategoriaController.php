<?php

namespace App\Http\Controllers;

use App\Models\NivelCategoria;
use App\Models\NivelGrado;
use App\Models\Grado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NivelCategoriaController extends Controller
{
    public function store(Request $request)
    {
        
        $nivelesGuardados = [];

        DB::beginTransaction();
        try {
            $index = 0;
            foreach ($request->all() as $nivelData) {
                $index++;
    
                $permiteSeleccion = $nivelData['grado_min'] != $nivelData['grado_max'];
    
                $nivel = NivelCategoria::create([
                    'nombre' => $nivelData['nombre'],
                    'id_area' => $nivelData['id_area'],
                    'permite_seleccion_nivel' => $permiteSeleccion
                ]);
    
                $grados = Grado::whereBetween('id_grado', [
                    $nivelData['grado_min'],
                    $nivelData['grado_max']
                ])->get();
    
                foreach ($grados as $grado) {
                    NivelGrado::create([
                        'id_nivel' => $nivel->id_nivel,
                        'id_grado' => $grado->id_grado
                    ]);
                }
    
                $nivelesGuardados[] = [
                    'index' => $index,
                    'nivel' => $nivel,
                    'grados_asociados' => $grados->pluck('id_grado')
                ];
            }
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear los niveles',
                'error'   => $e->getMessage(),
                'status'  => 500
            ], 500);
        }
    
        return response()->json([
            'message' => 'Niveles creados exitosamente',
            'niveles_guardados' => $nivelesGuardados,
            'status'  => 201
        ], 201);
    }
}
