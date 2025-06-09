<?php

namespace App\Modules\Olympiad\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Olympiad\Models\Categorylevel;
use App\Modules\Olympiad\Models\Grade;
use App\Modules\Olympiad\Models\GradeLevel;
use App\Modules\Olympiad\Models\AreaLevelOlympiad;
use App\Modules\Olympiad\Models\Olympiad;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CategoryLevelController extends Controller
{
    public function associateLevelsByArea(Request $request)
    {
        $data = $request->validate([
            'id_olympiad' => 'required|integer|exists:olympiad,id_olympiad',
            'id_area' => 'required|integer|exists:area,id_area',
            'id_categories' => 'required|array|min:1',
            'id_categories.*' => 'required|integer|exists:category_level,id_level',
         //   'max_niveles' => 'required|integer|min:1'
        ]);

        $inserted = [];

        DB::beginTransaction();
        try {
            foreach ($data['id_categories'] as $idLevel) {
                $exists = AreaLevelOlympiad::where([
                    'id_olympiad' => $data['id_olimpiada'],
                    'id_area' => $data['id_area'],
                    'id_level' => $idLevel
                ])->exists();

                if (!$exists) {
                    AreaLevelOlympiad::create([
                        'id_olympiad' => $data['id_olympiad'],
                        'id_area' => $data['id_area'],
                        'id_level' => $idLevel,
                        //'max_niveles' => $data['max_niveles'] 
                    ]);

                    $inserted[] = $idLevel;
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Levels successfully associated.',
                'associated levels' => $inserted
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error associating levels to the area.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function associateGrades(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'id_level' => 'required|integer|exists:category_level,id_level',
            'id_grade_min' => 'required|integer|exists:grade,id_grade',
            'id_grade_max' => 'required|integer|exists:grade,id_grade',
            'id_olympiad' => 'required|integer|exists:olympiad,id_olympiad',
        ]);

        // Verificar si ya existe una asociación con la misma olimpiada y nivel
        $associationExisting = GradeLevel::where('id_olympiad', $request->id_olympiad)
            ->where('id_level', $request->id_level)
            ->first();

        if ($associationExisting) {
            return response()->json([
                'success' => false,
                'message' => 'There is already an association with this olympiad and level',
                'existing_association' => $associationExisting
            ], 409); // 409 Conflict
        }
        // Obtener todos los grados en el rango especificado
        $grades = Grade::whereBetween('id_grade', [$request->id_grade_min, $request->id_grade_max])
            ->orderBy('id_grade')
            ->get();

        $createdAssociations = 0;
        $associations = [];

        // Crear las asociaciones para cada grado en el rango
        foreach ($grades as $grade) {
            $gradeLevel = GradeLevel::create([
                'id_level' => $request->id_level,
                'id_grade' => $grade->id_grade,
                'id_olympiad' => $request->id_olympiad
            ]);
            $createdAssociations++;
            $associations[] = $gradeLevel;
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully created associations',
            'detail' => [
                    'new_associations' => $createdAssociations,
                    'total_processed_grades' => $grades->count()
                ],
            'id_level' => $request->id_level,
            'grade_range' => [
                'min' => $request->id_grade_min,
                'max' => $request->id_grade_max
            ],
            'associations' => $associations
        ], 201); 
    }
    public function getById($idOlympiad)
    {
        $olympiad = Olympiad::find($idOlympiad);
        if (!$olympiad) {
            return response()->json([
                'success' => false,
                'message' => 'The specified Olympiad does not exist.'
            ], 404);
        }
        $levels = CategoryLevel::whereHas('grade_level', function($query) use ($idOlympiad) {
                    $query->where('id_olympiad', $idOlympiad)
                    ->orWhereNull('id_olympiad');
                })
                ->with(['grados' => function($query) use ($idOlympiad) {
                    $query->whereHas('grade_level', function($q) use ($idOlympiad) {
                        $q->where('id_olympiad', $idOlympiad)
                        ->orWhereNull('id_olympiad');
                    });
                }])
        ->get();
        $response = $levels->map(function ($level) use ($idOlympiad) {
            $olympiadGrades = $level->grades->filter(function ($grade) use ($idOlympiad) {
                return $grade->grade_level->contains('id_olympiad', $idOlympiad);
            });
            $nullGrades = $level->grades->filter(function ($grade) {
                return $grade->grade_level->contains('id_olympiad', null);
            });

            // Combinar ambos conjuntos, eliminando duplicados
            $allGrades = $olympiadGrades->merge($nullGrades)->unique('id_grade');
                return [
                'id_level' => $level->id_level,
                'level_name' => $level->name,
                'grades' => $allGrades->map(function ($grade) {
                    return [
                        'id_grade' => $grade->id_grade,
                        'grade_name' => $grade->grade_name,
                    ];
                })->values()
            ];
        });
        return response()->json(
            $response,
        );
    }
    public function show($idOlympiad)
    {
        $olympiad = Olympiad::find($idOlympiad);
        if (!$olympiad) {
            return response()->json([
                'success' => false,
                'message' => 'The specified Olympiad does not exist.'
            ], 404);
        }
        $associatedLevels = GradeLevel::where('id_olympiad', $idOlympiad)
            ->distinct()
            ->pluck('id_level')
            ->toArray();

        $availableLevels = Categorylevel::where('id_level', '>', 12)
            ->whereNotIn('id_nivel', $associatedLevels)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List of levels available for that Olympiad',
            'levels' => $availableLevels
            ], 200);
    }
    public function getByNivelesById($idOlympiad)
    {
        $olympiad = Olympiad::find($idOlympiad);
        if (!$olympiad) {
            return response()->json([
                'success' => false,
                'message' => 'The specified Olympiad does not exist.'
            ], 404);
        }

        $areaWithLevels = AreaLevelOlympiad::where('id_olympiad', $idOlympiad)
            ->pluck('id_level')
            ->toArray();

        $availableLevels = GradeLevel::with('category_level')
            ->where(function($query) use ($idOlympiad, $areaWithLevels) {
                $query->where('id_olympiad', $idOlympiad)
                    ->whereNotIn('id_level', $areaWithLevels);
            })
            ->orWhere(function($query) {
                $query->whereNull('id_olympiad');
            })
            ->get()
            ->unique('id_level')
            ->map(function ($item) {
                return [
                    'id_level' => $item->id_nivel,
                    'name' => $item->level->name,
                ];
            })
            ->values();
        return response()->json([
            'success' => true,
            'message' => 'Levels available without assigned area',
            'levels' => $availableLevels,
        ]);
    }
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50|unique:category_level,name'
            ]);
            $level = CategoryLevel::create([
                'name' => trim($validated['name'])
            ]);
            return response()->json([
                'message' => 'Nivel creado correctamente.',
                'level' => $level
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->validator->errors()->first()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Unespected error happend',
                'detail' => $e->getMessage()
            ], 500);
        }
    }
    // // REVISAR!!!
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:50',
    //         'id_area' => 'required|integer|exists:areas_competencia,id_area',
    //         'grado_min' => 'required|integer|exists:grado,id_grado',
    //         'grado_max' => 'required|integer|exists:grado,id_grado',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         // 1. Buscar el id_nivel basado en el nombre
    //         $nivel = NivelCategoria::where('nombre', $data['nombre'])->first();

    //         if (!$nivel) {
    //             return response()->json([
    //                 'message' => 'Nivel no encontrado en el catálogo.',
    //                 'nombre' => $data['nombre']
    //             ], 404);
    //         }

    //         // 2. Asociarlo al área de competencia
    //         NivelAreaOlimpiada::create([
    //             'id_olimpiada' => 1, // Ajustar si manejas varias olimpiadas
    //             'id_area' => $data['id_area'],
    //             'id_nivel' => $nivel->id_nivel,
    //             'max_niveles' => 1, // O ajustar si quieres
    //         ]);

    //         // 3. Asociarlo a los grados
    //         for ($grado = $data['grado_min']; $grado <= $data['grado_max']; $grado++) {
    //             NivelGrado::create([
    //                 'id_nivel' => $nivel->id_nivel,
    //                 'id_grado' => $grado,
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'message' => 'Nivel asociado correctamente a área y grados.',
    //             'nivel' => $nivel
    //         ], 201);

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Error interno al asociar el nivel.',
    //             'error' => $e->getMessage(),
    //             'line' => $e->getLine()
    //         ], 500);
    //     }
    // }
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
    // public function indexNow()//nose pero creo que usaba para algo xd
    // {
    //     $fechaActual = now(); // O Carbon::now() si usas Carbon
        
    //     $niveles = NivelCategoria::whereHas('grados.nivelGradoPivot.olimpiada', function($query) use ($fechaActual) {
    //             $query->where('fecha_inicio', '>=', $fechaActual);
    //         })
    //         ->with(['grados' => function($query) {
    //             $query->withPivot('id_olimpiada');
    //         }, 'nivelGradoPivot.olimpiada' => function($query) use ($fechaActual) {
    //             $query->where('fecha_inicio', '>=', $fechaActual)
    //                 ->select('id_olimpiada', 'nombre_olimpiada', 'fecha_inicio', 'fecha_fin');
    //         }])
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
    public function index()
    {
        $levels = CategoryLevel::where('id_level', '>', 12)->get();
        
        return response()->json([
            'message' => 'Lista de niveles cargada correctamente. (a partir del id 13).',
            'levels' => $levels
        ], 200);
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

    
}
