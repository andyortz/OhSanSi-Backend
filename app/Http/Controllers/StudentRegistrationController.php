<?php

namespace App\Http\Controllers;

use App\Models\StudentRegistration;
use App\Http\Requests\StoreStudentRegistrationRequest;

class StudentRegistrationController extends Controller
{
    public function index()
    {
        $students = StudentRegistration::all();
        return response()->json($students, 200);
    }

    public function store(StoreStudentRegistrationRequest $request)
    {
        try {
            $student = StudentRegistration::create($request->validated());    
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
}
