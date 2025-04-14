<?php

namespace App\Http\Controllers;

use App\Models\Olimpista;
use App\Models\Parentesco;
use App\Models\Tutor;
use App\Http\Requests\StoreOlimpistaRequest;

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

    public function store(StoreOlimpistaRequest $request)
    {
        try {
            $data = $request->validated();
            $ciTutor = $data['ci_tutor'];
            unset($data['ci_tutor']);
    
            $olimpista = Olimpista::create($data);
            $tutor = Tutor::where('ci', $ciTutor)->first();
    
            if (!$tutor) {
                return response()->json(['message' => 'No se encontró un tutor con esa cédula'], 404);
            }
    
            Parentesco::create([
                'id_olimpista' => $olimpista->id_olimpista,
                'id_tutor' => $tutor->id_tutor,
                'rol_parentesco' => 'Tutor Legal'
            ]);
    
            return response()->json([
                'message' => 'Olimpista y vínculo con tutor creados correctamente',
                'olimpista' => $olimpista
            ], 201);
    
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}