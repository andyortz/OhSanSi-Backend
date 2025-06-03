<?php

namespace App\Http\Controllers;

use App\Models\NivelCategoria;
use App\Models\Grado;
use App\Models\NivelGrado;
use App\Models\NivelAreaOlimpiada;
use App\Models\Olimpiada;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class NivelCategoriaController extends Controller
{
    public function asociarNivelesPorArea(Request $request)
    {
        $data = $request->validate([
            'id_olimpiada' => 'required|integer|exists:olimpiada,id_olimpiada',
            'id_area' => 'required|integer|exists:area_competencia,id_area',
            'id_categorias' => 'required|array|min:1',
            'id_categorias.*' => 'required|integer|exists:nivel_categoria,id_nivel',
         //   'max_niveles' => 'required|integer|min:1'
        ]);

        $insertadas = [];

        DB::beginTransaction();
        try {
            foreach ($data['id_categorias'] as $idNivel) {
                $yaExiste = NivelAreaOlimpiada::where([
                    'id_olimpiada' => $data['id_olimpiada'],
                    'id_area' => $data['id_area'],
                    'id_nivel' => $idNivel
                ])->exists();

                if (!$yaExiste) {
                    NivelAreaOlimpiada::create([
                        'id_olimpiada' => $data['id_olimpiada'],
                        'id_area' => $data['id_area'],
                        'id_nivel' => $idNivel,
                        //'max_niveles' => $data['max_niveles'] 
                    ]);

                    $insertadas[] = $idNivel;
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Niveles asociados exitosamente.',
                'niveles_asociados' => $insertadas
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al asociar niveles al área.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
            'id_area' => 'required|integer|exists:areas_competencia,id_area',
            'grado_min' => 'required|integer|exists:grado,id_grado',
            'grado_max' => 'required|integer|exists:grado,id_grado',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buscar el id_nivel basado en el nombre
            $nivel = NivelCategoria::where('nombre', $data['nombre'])->first();

            if (!$nivel) {
                return response()->json([
                    'message' => 'Nivel no encontrado en el catálogo.',
                    'nombre' => $data['nombre']
                ], 404);
            }

            // 2. Asociarlo al área de competencia
            NivelAreaOlimpiada::create([
                'id_olimpiada' => 1, // Ajustar si manejas varias olimpiadas
                'id_area' => $data['id_area'],
                'id_nivel' => $nivel->id_nivel,
                'max_niveles' => 1, // O ajustar si quieres
            ]);

            // 3. Asociarlo a los grados
            for ($grado = $data['grado_min']; $grado <= $data['grado_max']; $grado++) {
                NivelGrado::create([
                    'id_nivel' => $nivel->id_nivel,
                    'id_grado' => $grado,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Nivel asociado correctamente a área y grados.',
                'nivel' => $nivel
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error interno al asociar el nivel.',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }


    public function nivelesPorArea($id_area)
    {
        $niveles = DB::table('niveles_areas_olimpiadas')
            ->join('niveles_categoria', 'niveles_areas_olimpiadas.id_nivel', '=', 'niveles_categoria.id_nivel')
            ->where('niveles_areas_olimpiadas.id_area', $id_area)
            ->select('niveles_categoria.id_nivel', 'niveles_categoria.nombre')
            ->groupBy('niveles_categoria.id_nivel', 'niveles_categoria.nombre')
            ->get();

        if ($niveles->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron niveles para esta área.',
                'niveles' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Niveles encontrados correctamente.',
            'niveles' => $niveles
        ], 200);
    }

    public function newCategoria(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:50|unique:nivel_categoria,nombre'
            ]);

            $nivel = NivelCategoria::create([
                'nombre' => trim($validated['nombre'])
            ]);

            return response()->json([
                'message' => 'Nivel creado correctamente.',
                'nivel' => $nivel
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->validator->errors()->first()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Ocurrió un error inesperado.',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
    
    public function asociarGrados(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'id_nivel' => 'required|integer|exists:nivel_categoria,id_nivel',
            'id_grado_min' => 'required|integer|exists:grado,id_grado',
            'id_grado_max' => 'required|integer|exists:grado,id_grado',
        ]);

        try {
            // Verificar que el nivel existe
            $nivel = NivelCategoria::findOrFail($request->id_nivel);
            
            // Obtener todos los grados entre el mínimo y el máximo
            $grados = Grado::whereBetween('id_grado', [$request->id_grado_min, $request->id_grado_max])
                          ->orderBy('id_grado')
                          ->get();

            $asociacionesCreadas = 0;
            $asociacionesExistentes = 0;

            // Crear las asociaciones
            foreach ($grados as $grado) {
                // Verificar si la asociación ya existe
                $existe = NivelGrado::where('id_nivel', $request->id_nivel)
                                    ->where('id_grado', $grado->id_grado)
                                    ->exists();

                if ($existe) {
                    $asociacionesExistentes++;
                    continue;
                }

                // Crear nueva asociación si no existe
                NivelGrado::create([
                    'id_nivel' => $request->id_nivel,
                    'id_grado' => $grado->id_grado
                ]);
                $asociacionesCreadas++;
            }
                
            return response()->json([
                'success' => true,
                'message' => 'Proceso de asociación completado',
                'detalle' => [
                    'asociaciones_nuevas' => $asociacionesCreadas,
                    'asociaciones_ya_existentes' => $asociacionesExistentes,
                    'total_grados_procesados' => $grados->count()
                ],
                'nivel_id' => $request->id_nivel,
                'rango_grados' => [
                    'min' => $request->id_grado_min,
                    'max' => $request->id_grado_max
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear las asociaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getById($idOlimpiada)
    {
        $niveles = NivelCategoria::whereHas('grados', function($query) use ($idOlimpiada) {
                // Filtro opcional por olimpiada
                if ($idOlimpiada) {
                    $query->whereHas('nivelGradoPivot', function($q) use ($idOlimpiada) {
                        $q->where('id_olimpiada', $idOlimpiada)
                        ->orWhereNull('id_olimpiada');
                    });
                }
            })
             ->with(['grados' => function($query) {
                $query->withPivot('id_olimpiada'); // Mantenemos el pivot original
            }, 'nivelGradoPivot.olimpiada' => function($query) use ($idOlimpiada) {
                // Filtramos las olimpiadas si se especificó un ID
                if ($idOlimpiada) {
                    $query->where('id_olimpiada', $idOlimpiada)
                        ->orWhereNull('id_olimpiada');
                }
            }])
            ->get();

        $response = $niveles->map(function ($nivel) {
            return [
                'id_nivel' => $nivel->id_nivel,
                'nombre_nivel' => $nivel->nombre,
                'grados' => $nivel->grados->map(function ($grado) {
                    return [
                        'id_grado' => $grado->id_grado,
                        'nombre_grado' => $grado->nombre_grado,
                    ];
                })->unique('id_grado')->values()
            ];
        });

        return response()->json($response);
    }

    public function index()
    {
        $fechaActual = now(); // O Carbon::now() si usas Carbon
        
        $niveles = NivelCategoria::whereHas('grados.nivelGradoPivot.olimpiada', function($query) use ($fechaActual) {
                $query->where('fecha_inicio', '>=', $fechaActual);
            })
            ->with(['grados' => function($query) {
                $query->withPivot('id_olimpiada');
            }, 'nivelGradoPivot.olimpiada' => function($query) use ($fechaActual) {
                $query->where('fecha_inicio', '>=', $fechaActual)
                    ->select('id_olimpiada', 'nombre_olimpiada', 'fecha_inicio', 'fecha_fin');
            }])
            ->get();

        $response = $niveles->map(function ($nivel) {
            return [
                'id_nivel' => $nivel->id_nivel,
                'nombre_nivel' => $nivel->nombre,
                'nombre_olimpiada' => optional($nivel->nivelGradoPivot->first()->olimpiada)->nombre_olimpiada ?? null,
                'grados' => $nivel->grados->map(function ($grado) {
                    return [
                        'id_grado' => $grado->id_grado,
                        'nombre_grado' => $grado->nombre_grado
                    ];
                })->unique('id_grado')->values()
            ];
        });

        return response()->json($response);
    }

    // public function index()
    // {
    //     $niveles = NivelCategoria::whereHas('grados')
    //         ->with(['grados' => function($query) {
    //             $query->withPivot('id_olimpiada');
    //         }, 'nivelGradoPivot.olimpiada'])
    //         ->get();

    //     $response = $niveles->map(function ($nivel) {
    //         return [
    //             'id_nivel' => $nivel->id_nivel,
    //             'nombre_nivel' => $nivel->nombre,
    //             'nombre_olimpiada' => optional($nivel->nivelGradoPivot->first()->olimpiada)->nombre_olimpiada ?? null,
    //             'grados' => $nivel->grados->map(function ($grado) {
    //                 return [
    //                     'id_grado' => $grado->id_grado,
    //                     'nombre_grado' => $grado->nombre_grado
    //                 ];
    //             })->unique('id_grado')->values()
    //         ];
    //     });

    //     return response()->json($response);
    // }

    public function index2()
    {
        $niveles = NivelCategoria::all();

        return response()->json([
            'message' => 'Lista de niveles cargada correctamente.',
            'niveles' => $niveles
        ], 200);
    }
    public function index3()
    {
        $niveles = NivelCategoria::where('id_nivel', '>', 12)->get();
        
        return response()->json([
            'message' => 'Lista de niveles cargada correctamente. (a partir del id 13).',
            'niveles' => $niveles
        ], 200);
    }
}
