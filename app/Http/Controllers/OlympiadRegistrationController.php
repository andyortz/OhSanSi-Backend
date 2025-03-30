<?php

namespace App\Http\Controllers;

use App\Models\OlympiadRegistration;
use App\Http\Requests\StoreOlympiadRequest;

class OlympiadRegistrationController extends Controller
{
    public function index()
    {
        $olympiads = OlympiadRegistration::all();
        return response()->json($olympiads, 200);
    }

    public function store(StoreOlympiadRequest $request) {
        try {
            $olimpiad = OlympiadRegistration::create($request->validated());    
            return response()->json([
                'message' => 'Olimpiada creada exitosamente',
                'olimpiada'   => $olimpiad
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la olimpiada',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}