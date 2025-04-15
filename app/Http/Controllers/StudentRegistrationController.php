<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentRegistrationController extends Controller
{
    public function store(Request $request)
    {
        // Implementar la lógica de almacenamiento aquí
        return response()->json(['message' => 'Registro de estudiante creado exitosamente']);
    }

    public function index()
    {
        // Implementar la lógica de listado aquí
        return response()->json(['message' => 'Lista de registros de estudiantes']);
    }
} 