<?php

namespace App\Http\Controllers;

use App\Modules\Olympist\Models\OlympistDetail;
use App\Modules\Olympiad\Models\Olympiad;
use App\Modules\Olympist\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OlympistController extends Controller
{
    public function enrollments($ci)
    {
        try {
            $olympistDetail = OlympistDetail::where('ci_olympist', $ci)->first();
            if (!$olympistDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Olympist not found'
                ], 404);
            }
            $currentOlympiad = Olympiad::whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();
            if (!$currentOlympiad) {
                return response()->json([
                    'success' => false,
                    'message' => 'There is no active Olympiad at this time'
                ], 404);
            }
            $enrollments = Enrollment::with([
                'category_level:id_level,name',
                'category_level.area_level_olympiad' => function($query) use ($currentOlympiad) {
                    $query->where('id_olympiad', $currentOlympiad->id_olympiad)
                        ->with('area:id_area,name');
                }
            ])
            ->where('id_olympist_detail', $olympistDetail->id_olympist_detail)
            ->get();

            $response = [
                'enrollments' => $enrollments->map(function ($enrollment) {
                    // Filter only valid associations (not null)
                    $validAssociation = $enrollment->category_level->area_level_olympiad->firstWhere('area', '!=', null);
                    
                    return [
                        'id_enrollment' => $enrollment->id_enrollment,
                        'level' => $enrollment->category_level ? [
                            'id_level' => $enrollment->category_level->id_level,
                            'name' => $enrollment->category_level->name
                        ] : null,
                        'area' => $validAssociation ? [
                            'id_area' => $validAssociation->area->id_area,
                            'name' => $validAssociation->area->name
                        ] : null
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching enrollments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected $repo;
    protected $olimpistaService;

    public function __construct(OlimpistaRepository $repo, OlimpistaService $olimpistaService)
    {
        $this->repo = $repo;
        $this->olimpistaService = $olimpistaService;
    }

    public function areasLevels($ci): JsonResponse
    {
        try {
            $data = $this->repo->areasLevels($ci);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombres' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'cedula_identidad' => 'required|integer|unique:persona,ci_persona',
                'correo_electronico' => 'required|email|max:100',
                'fecha_nacimiento' => 'required|date',
                'unidad_educativa' => 'required|integer',
                'id_grado' => 'required|exists:grado,id_grado', 
                'celular' => 'nullable|string|max:8',
                'ci_tutor' => 'required',
            ]);

            
            $persona = $this->olimpistaService->register($validated);

            return response()->json([
                'message' => 'Olimpista registrado exitosamente.',
                'persona' => $persona
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->validator->errors()->first()
            ], 422);

        } catch (\Throwable $e) {
            $statusCode = $e->getCode() === 409 ? 409 : 500;

            return response()->json([
                'error' => $e->getMessage()
            ], $statusCode);
        }
    }
}
