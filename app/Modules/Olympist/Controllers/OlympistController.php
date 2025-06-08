<?php

namespace App\Http\Controllers;

use App\Modules\Olympist\Models\OlympistDetail;
use App\Modules\Olympiad\Models\Olympiad;
use App\Modules\Olympist\Models\Enrollment;
use App\Services\Registers\OlympistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OlympistController extends Controller
{
    // protected $repo;
    protected $olimpistaService;

    public function __construct(OlympistService $olimpistaService)
    {
        //OlimpistaRepository $repo, 
        // $this->repo = $repo;
        $this->olympistService = $olympistService;
    }

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
    // public function getAreasNivelesInscripcion($ci): JsonResponse
    // {
    //     try {
    //         $data = $this->repo->getAreasNiveles($ci);
    //         return response()->json($data);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 404);
    //     }
    // }
    // public function areasLevels($ci): JsonResponse
    // {
    //     try {
    //         $data = $this->repo->areasLevels($ci);
    //         return response()->json($data);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 404);
    //     }
    // }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'names' => 'required|string|max:100',
                'surname' => 'required|string|max:100',
                'ci' => 'required|integer|unique:person,ci_person',
                'email' => 'required|email|max:100',
                'birthdate' => 'required|date',
                'school' => 'required|integer',
                'id_grade' => 'required|exists:gradE,id_gradE', 
                'phone' => 'nullable|string|max:8',
                'ci_tutor' => 'required',
            ]);

            
            $person = $this->olympistService->register($validated);

            return response()->json([
                'message' => 'Olimpista registrado exitosamente.',
                'person' => $person
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

    public function getByCedula($ID): JsonResponse
    {
        $person = Persona::with(['olympicDetail.grade', 'olympicDetail.school.province.departament'])
            ->where('ci_person', $ID)
            ->first();

        if (!$person) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        $response = [
            'ci_person' => $person->ci_persona,
            'names' => $person->nombres,
            'surnames' => $person->apellidos,
            'birthdate' => $person->fecha_nacimiento,
            'email' => $person->correo_electronico,
            'phone' => $person->celular,
            'ci_tutor_guardia' => $person->detalleOlimpista->ci_tutor_legal ?? null,
            'id_departament' => $person->detalleOlimpista->colegio->provincia->id_departamento ?? null,
            'id_province' => $person->detalleOlimpista->colegio->id_provincia ?? null,
            'id_school' => $person->detalleOlimpista->unidad_educativa ?? null,
            'id_grade' => $person->detalleOlimpista->id_grado ?? null,
            'id_olympiad' => $person->detalleOlimpista->id_olimpiada ?? null,
        ];

        return response()->json($response);
    }
    public function getByEmail($email): JsonResponse
    {
        $person = Person::where('email', $email)->first();

        return $person
            ? response()->json($person)
            : response()->json(['message' => 'No encontrado'], 404);
    }
}
