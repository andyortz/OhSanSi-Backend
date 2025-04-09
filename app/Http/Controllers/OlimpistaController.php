<?php

namespace App\Http\Controllers;

use App\Models\Olimpista;
use App\Http\Requests\StoreStudentRegistrationRequest;

class OlimpistaController extends Controller
{

    public function getByCedula($cedula)
    {
        $olimpista = Olimpista::where('cedula_identidad', $cedula)->first();
    
        return $olimpista 
            ? response()->json($olimpista)
            : response()->json(['message' => 'No encontrado'], 404);
    }
    
    public function getByEmail($email)
    {
        $olimpista = Olimpista::where('correo_electronico', $email)->first();
    
        return $olimpista
            ? response()->json($olimpista)
            : response()->json(['message' => 'No encontrado'], 404);
    }

    public function store(StoreStudentRegistrationRequest $request)
    {
        try {
            $student = Olimpista::create($request->validated());    
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