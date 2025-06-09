<?php

namespace App\Modules\Olympist\Controllers;

use App\Modules\Olympist\Models\Person;
use App\Modules\Olympist\Requests\StorePersonRequest;
use App\Services\Registers\PersonService;


use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function show($ci)
    {
        $person = Person::where('ci_person', $ci)->first();

        if ($person) {
            return response()->json([
                'message' => 'Person found',
                'person' => $person,
                'status' => 200
            ], 200);
        }
        
        return response()->json([
            'message' => 'Person not found',
            'status' => 404
        ], 404);
    }
    public function store(StorePersonRequest $request)
    {
        try {
            $validated = $request->validated();

            $person = PersonService::register($validated);

            return response()->json([
            'message' => 'Person registered successfully',
            'person' => $person,
            'status' => 201
        ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error registering person',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}