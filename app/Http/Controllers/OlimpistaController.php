<?php

namespace App\Http\Controllers;

use App\Models\Olimpista;
use App\Http\Requests\StoreOlimpistaRequest;

class OlimpistaController extends Controller
{
    public function getByCedula($cedula)
    {
        $olimpista = Olimpista::where('cedula_identidad', $cedula)->first();
    
        return $olimpista 
            ? response()->json($olimpista)
            : response()->json(['message' => 'No encontrado'], 404);
    }
    
    public function getByEmail($email)
    {
        $olimpista = Olimpista::where('correo_electronico', $email)->first();
    
        return $olimpista
            ? response()->json($olimpista)
            : response()->json(['message' => 'No encontrado'], 404);
    }

    public function store(StoreOlimpistaRequest $request)
    {
        try {
            $student = StoreOlimpistaRequest::create($request->validated());    
            return response()->json([
                'message' => 'Olimpista creado exitosamente',
                'olimpista'   => $student
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear al olimpista',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function getDetallesInscripcion($ci)
    {
        // 1. Obtener el olimpista con su grado
        $olimpista = Olimpista::with('id_grado')->where('ci', $ci)->first();
        if (!$olimpista) {
            return response()->json([
                'success' => false,
                'message' => 'Olimpista no encontrado'
            ], 404);
        }
        // 2. Obtener niveles asociados al grado del olimpista
        $niveles = $olimpista->id_grado->niveles()
            ->with('categoria')
            ->get()
            ->map(function ($nivel) {
                return [
                    'id_nivel' => $nivel->id,
                    'nombre_nivel' => $nivel->nombre_nivel,
                    'categoria' => $nivel->categoria
                ];
            });
        // 3. Obtener áreas disponibles con sus niveles permitidos
            $areas = AreaCompetencia::with(['nivelesAreas' => function($query) use ($olimpista) {
                $query->where('id_olimpiada', config('app.current_olympiad')) // Asume una olimpiada actual configurada
                      ->whereIn('id_nivel', $olimpista->grado->niveles->pluck('id'));
            }])
            ->get()
            ->map(function ($area) {
                return [
                    'id_area' => $area->id,
                    'nombre_area' => $area->nombre,
                    'niveles_permitidos' => $area->nivelesAreas->map(function ($nivelArea) {
                        return [
                            'id_nivel' => $nivelArea->id_nivel,
                            'max_niveles' => $nivelArea->max_niveles
                        ];
                    })
                ];
            });

        // 4. Filtrar áreas que tengan al menos un nivel permitido
        $areasFiltradas = $areas->filter(function ($area) {
            return $area['niveles_permitidos']->isNotEmpty();
        })->values();

        return response()->json([
            'success' => true,
            'olimpista' => [
                'id' => $olimpista->id,
                'nombres' => $olimpista->nombre,
                'apellidos' => $olimpista->apellido,
                'ci' => $olimpista->ci,
                'grado' => $olimpista->grado->nombre
            ],
            'niveles_disponibles' => $niveles,
            'areas_con_niveles' => $areasFiltradas
        ]);
    }
}