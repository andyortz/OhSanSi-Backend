<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOlimpistaRequest;
use App\Models\Persona;
use App\Repositories\OlimpistaRepository;
use App\Services\Registers\OlimpistaService;

use Illuminate\Http\JsonResponse;

class OlimpistaController extends Controller
{
    protected $repo;
    protected $olimpistaService;

    public function __construct(OlimpistaRepository $repo, OlimpistaService $olimpistaService)
    {
        $this->repo = $repo;
        $this->olimpistaService = $olimpistaService;
    }

    public function getAreasNivelesInscripcion($ci): JsonResponse
    {
        try {
            $data = $this->repo->getAreasNiveles($ci);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(StoreOlimpistaRequest $request): JsonResponse
    {
        try {
            $persona = $this->olimpistaService->register($request->validated());

            return response()->json([
                'message' => 'Olimpista registrado exitosamente.',
                'persona' => $persona
            ], 201);

        } catch (\Throwable $e) {
            $statusCode = $e->getCode() === 409 ? 409 : 500;

            return response()->json([
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ], $statusCode);
        }
    }

    public function getByCedula($cedula): JsonResponse
    {
        $persona = Persona::where('ci_persona', $cedula)->first();

        return $persona
            ? response()->json($persona)
            : response()->json(['message' => 'No encontrado'], 404);
    }

    public function getByEmail($email): JsonResponse
    {
        $persona = Persona::where('correo_electronico', $email)->first();

        return $persona
            ? response()->json($persona)
            : response()->json(['message' => 'No encontrado'], 404);
    }
}
