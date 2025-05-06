<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOlimpistaRequest;
use App\Models\Persona;
use App\Repositories\OlimpistaRepository;
use App\Services\Registers\OlimpistaService;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

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

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombres' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'cedula_identidad' => 'required|integer|unique:persona,ci_persona',
                'correo_electronico' => 'required|email|max:100|unique:persona,correo_electronico',
                'fecha_nacimiento' => 'required|date',
                'unidad_educativa' => 'required|string|max:255',
                'id_grado' => 'required|exists:grado,id_grado',
                'celular' => 'nullable|string|max:8',
                'ci_tutor' => 'required|exists:persona,ci_persona',
            ]);

            $persona = $this->olimpistaService->register($validated);

            return response()->json([
                'message' => 'Olimpista registrado exitosamente.',
                'persona' => $persona
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
