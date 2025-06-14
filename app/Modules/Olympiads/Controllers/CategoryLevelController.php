<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\CategoryLevel;
use App\Modules\Olympiads\Models\Grade;
use App\Modules\Olympiads\Models\LevelGrade;
use App\Modules\Olympiads\Models\OlympiadAreaLevel;
use App\Modules\Olympiads\Models\Olympiad;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CategoryLevelController
{
    public function index()
    {
        $levels = CategoryLevel::where('level_id', '>', 12)->get();
        
        return response()->json([
            'message' => 'Lista de niveles cargada correctamente. (a partir del id 13).',
            'niveles' => $levels
        ], 200);
    }

    public function store(Request $request)
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
                'error' => 'Ocurrió un error inesperado.',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function getByOlympiad($id)
    {
        $olympiad = Olimpiada::find($id);
        if (!$olympiad) {
            return response()->json([
                'success' => false,
                'message' => 'La olimpiada especificada no existe.'
            ], 404);
        }
        $associatedLevels = LevelGrade::where('olympiad_id', $id)
            ->distinct()
            ->pluck('level_id')
            ->toArray();
        $availableLevels = CategoryLevel::where('level_id', '>', 12)
            ->whereNotIn('level_id', $associatedLevels)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Lista de niveles disponibles para esa olimpiada',
            'levels' => $availableLevels
        ], 200);
    }
    
    public function associateLevelsWithArea(Request $request)
    {
        $data = $request->validate([
            'olympiad_id' => 'required|integer|exists:olympiad,olympiad_id',
            'area_id' => 'required|integer|exists:area,area_id',
            'level_id.' => 'required|integer|exists:category_level,level_id',
        ]);
        DB::beginTransaction();
        try {
            $exist = OlympiadAreaLevel::where([
                'olympiad_id' => $request->olympiad_id,
                'area_id' => $request->area_id,
                'level_id' => $request->level_id
            ])->exists();
            if (!$exist) {
                OlympiadAreaLevel::create([
                    'olympiad_id' => $request->olympiad_id,
                    'area_id' => $request->area_id,
                    'level_id' => $request->level_id
                ]);
            }
            DB::commit();
            return response()->json([
                'message' => 'Nivel asociado exitosamente.',
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al asociar niveles al área.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function associateGrades(Request $request)
    {
        $request->validate([
            'level_id' => 'required|integer|exists:category_level,level_id',
            'min_grade_id' => 'required|integer|exists:grade,grade_id',
            'max_grade_id' => 'required|integer|exists:grade,grade_id',
            'olympiad_id' => 'required|integer|exists:olympiad,olympiad_id',
        ]);

        $exist = LevelGrade::where('olympiad_id', $request->olympiad_id)
            ->where('level_id', $request->level_id)
            ->first();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una asociación con esta olimpiada y nivel',
                'existing_association' => $exist
            ], 409);
        }
        $grades = Grade::whereBetween('grade_id', [$request->min_grade_id, $request->max_grade_id])
            ->orderBy('grade_id')
            ->get();

        $createdAssociations = 0;
        $associations = [];

        foreach ($grades as $grade) {
            $levelGrade = LevelGrade::create([
                'level_id' => $request->level_id,
                'grade_id' => $grado->grade_id,
                'olympiad_id' => $request->olympiad_id
            ]);
            $createdAssociations++;
            $associations[] = $levelGrade;
        }

        return response()->json([
            'success' => true,
            'message' => 'Asociaciones creadas exitosamente',
            'detalle' => [
                    'asociaciones_nuevas' => $createdAssociations,
                    'total_grados_procesados' => $grades->count()
                ],
            'level_id' => $request->level_id,
            'rango_grados' => [
                'min' => $request->min_grade_id,
                'max' => $request->max_grade_id
            ],
            'associations' => $associations
        ], 201);
    }

    public function getById($id)
    {
        $olympiad = olympiad::find($id);
        if (!$olympiad) {
            return response()->json([
                'success' => false,
                'message' => 'La olimpiada especificada no existe.'
            ], 404);
        }
        $levels = CategoryLevel::whereHas('gradeLevelPivot', function($query) use ($id) {
            $query->where('olympiad_id', $id)
                ->orWhereNull('olympiad_id');
        })
        ->with(['grades' => function($query) use ($id) {
            $query->whereHas('gradeLevelPivot', function($q) use ($id) {
                $q->where('olympiad_id', $id)
                ->orWhereNull('olympiad_id');
            });
        }])
        ->get();
        $response = $levels->map(function ($level) use ($id) {
            $olympiadGrades = $level->grades->filter(function ($grade) use ($id) {
                return $grade->gradeLevelPivot->contains('olympiad_id', $id);
            });
            $gradesNull = $level->grades->filter(function ($grade) {
                return $grade->gradeLevelPivot->contains('olympiad_id', null);
            });
            $allGrades = $olympiadGrades->merge($gradesNull)->unique('grado_id');
                return [
                'level_id' => $level->level_id,
                'level_name' => $level->name,
                'grades' => $allGrades->map(function ($grade) {
                    return [
                        'grade_id' => $grade->grado_id,
                        'grade_name' => $grade->name,
                    ];
                })->values()
            ];
        });
        return response()->json(
            $response,
        );
    }

    public function getByNivelesById($id)
    {
        $olympiad = Olympiad::find($id);
        if (!$olympiad) {
            return response()->json([
                'success' => false,
                'message' => 'La olimpiada especificada no existe.'
            ], 404);
        }
        $levelsWithArea = OlympiadAreaLevel::where('olympiad_id', $id)
            ->pluck('level_id')
            ->toArray();
        $availableLevels = NivelGrado::with('nivel')
            ->where(function($query) use ($id, $levelsWithArea) {
                $query->where('olympiad_id', $id)
                    ->whereNotIn('level_id', $levelsWithArea);
            })
            ->orWhere(function($query) {
                $query->whereNull('olympiad_id');
            })
            ->get()
            ->unique('level_id')
            ->map(function ($item) {
                return [
                    'level_id' => $item->level_id,
                    'name' => $item->nivel->name,
                ];
            })
            ->values();
        return response()->json([
            'success' => true,
            'message' => 'Niveles disponibles sin área asignada',
            'niveles' => $availableLevels,
        ]);
    }
}