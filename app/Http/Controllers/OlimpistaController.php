<?php

namespace App\Http\Controllers;

use App\Models\Olimpista;
use App\Http\Requests\StoreOlimpistaRequest;
use App\Repositories\OlimpistaRepository;

class OlimpistaController extends Controller
{
    protected $repo;
    
    public function __construct(OlimpistaRepository $repo)
    {
        $this->repo = $repo;
    }
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
            $student = StoreOlimpistaRequest::create($request->validated());    
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
    public function getAreasNivelesInscripcion($ci)
    {
        try {
            $data = $this->repo->getAreasNiveles($ci);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}