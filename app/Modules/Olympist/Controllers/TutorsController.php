<?php

namespace App\Modules\Olympist\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Modules\Models\Olympist\Person;
use App\Services\Registers\PersonService;
use App\Modules\Olympist\Requests\StorePersonRequest;
use Illuminate\Http\Request;

class TutorsController extends Controller
{
    //Person show
    public function searchByCi($ci)
    {
        $tutor = Person::where('ci_person', $ci)->first();

        if ($tutor) {
            return response()->json([
                'message' => 'Tutor found',
                'tutor' => $tutor,
                'status' => 200
            ], 200);
        }

        return response()->json([
            'message' => 'Tutor not found',
            'status' => 404
        ], 404);
    }

    public function getByEmail($email)
    {
        $tutor = Person::where('email', $email)->first();

        return $tutor
            ? response()->json($tutor)
            : response()->json(['message' => 'No encontrado'], 404);
    }

    public function store(StorePersonRequest $request)
    {
        try {
            $validated = $request->validated();

            $person = PersonService::register($validated);

            return response()->json([
                'message' => 'Tutor successfully registered',
                'tutor' => $person,
                'status' => 201
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error registering tutor',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
