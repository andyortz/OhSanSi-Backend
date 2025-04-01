<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AreasController extends Controller
{
    /**
     * Obtener todas las áreas
     */
    public function index()
    {
        $areas = Area::all();
        return response()->json($areas, 200);
    }

    /**
     * Obtener áreas por ID de olimpiada
     */
    public function areasPorOlimpiada($id_olimpiada)
    {
        $areas = Area::where('id_olimpiada', $id_olimpiada)->get();

        if ($areas->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron áreas para la olimpiada especificada.',
                'areas' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Áreas encontradas exitosamente.',
            'areas' => $areas
        ], 200);
    }

    /**
     * Registrar una nueva área
     */
    public function store(Request $request)
    {
        // Decodificar JSON de 'areas'
        $areas = json_decode($request->input('areas'), true);

        if (!is_array($areas) || empty($areas)) {
            return response()->json([
                'message' => 'Debe enviar al menos un área válida.',
                'status' => 400
            ], 400);
        }

        // Guardar la imagen
        if (!$request->hasFile('imagen')) {
            return response()->json([
                'message' => 'La imagen es obligatoria.',
                'status' => 400
            ], 400);
        }
        $imagePath = $request->file('imagen')->store('areas', 'public');

        foreach ($areas as $areaData) {
            if (!isset($areaData['nombre'])) {
                continue; 
            }

            $areaExiste = DB::table('areas_competencia')
                ->whereRaw('LOWER(nombre) = ?', [strtolower($areaData['nombre'])])
                ->first();

            if ($areaExiste) {
                continue; 
            }

            Area::create([
                'id_olimpiada' => $request->id_olimpiada,
                'nombre' => $areaData['nombre'],
                'imagen' => $imagePath
            ]);
        }

        return response()->json([
            'message' => 'Áreas registradas exitosamente',
            'status' => 201
        ], 201);
    }

    public function areasConNivelesYGrados()
    {
        $areas = Area::with(['niveles.grados'])->get()->map(function ($area) {
            return [
                'id_area' => $area->id_area,
                'nombre_area' => $area->nombre,
                'niveles' => $area->niveles->map(function ($nivel) {
                    return [
                        'id_nivel' => $nivel->id_nivel,
                        'nombre_nivel' => $nivel->nombre,
                        'permite_seleccion_nivel' => $nivel->permite_seleccion_nivel,
                        'grados' => $nivel->grados->map(function ($grado) {
                            return [
                                'id_grado' => $grado->id_grado,
                                'nombre_grado' => $grado->nombre_grado
                            ];
                        })
                    ];
                })
            ];
        });

        return response()->json($areas, 200);
    }
}
