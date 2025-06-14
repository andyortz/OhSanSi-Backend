<?php

namespace App\Modules\Persons\Controllers;


use App\Modules\Persons\Models\Person;
use App\Repositories\OlympistRepository;
use App\Services\Registers\OlympistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OlympistController
{
    protected $repo;
    protected $olympistService;

    public function __construct(OlympistRepository $repo, OlympistService $olympistService)
    {
        $this->repo = $repo;
        $this->olympistService = $olympistService;
    }

    public function getEnrollmentAreaLevels($ci): JsonResponse
    {
        try {
            $data = $this->repo->getLevelAreas($ci);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'names' => 'required|string|max:100',
                'surnames' => 'required|string|max:100',
                'olympist_ci' => 'required|integer|unique:person,person_ci',
                'email' => 'required|email|max:100',
                'birthdate' => 'required|date',
                'school' => 'required|integer',
                'grade_id' => 'required|exists:grade,grade_id', 
                'phone' => 'nullable|string|max:15',
                'tutor_ci' => 'required',
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

    public function getByCi($ci): JsonResponse
    {
        $person = Person::with(['olympistDetail.grade', 'olympistDetail.school.province.department'])
            ->where('person_ci', $ci)
            ->first();

        if (!$person) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        $response = [
            'person_ci' => $person->person_ci,
            'names' => $person->names,
            'surnames' => $person->surnames,
            'birthdate' => $person->birthdate,
            'email' => $person->email,
            'phone' => $person->phone,
            'guardian_legal_ci' => $person->olympistDetail->guardian_legal_ci ?? null,
            'department_id' => $person->olympistDetail->school->province->department_id ?? null,
            'province_id' => $person->olympistDetail->school->province_id ?? null,
            'school_id' => $person->olympistDetail->school ?? null,
            'grade_id' => $person->olympistDetail->grade_id ?? null,
            'olympiad_id' => $person->olympistDetail->olympiad_id ?? null,
        ];

        return response()->json($response);
    }
    
    //ojito, no se usa
    public function getByEmail($email): JsonResponse
    {
        $person = Persona::where('correo_electronico', $email)->first();

        return $person
            ? response()->json($person)
            : response()->json(['message' => 'No encontrado'], 404);
    }
}
