<?php

namespace App\Http\Controllers;


use App\Models\Persona;
use App\Repositories\OlimpistaRepository;
use App\Services\Registers\OlimpistaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                'correo_electronico' => 'required|email|max:100',
                'fecha_nacimiento' => 'required|date',
                'unidad_educativa' => 'required|integer',
                'id_grado' => 'required|exists:grado,id_grado', 
                'celular' => 'nullable|string|max:8',
                'ci_tutor' => 'required',
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
        $persona = Persona::with(['detalleOlimpista.grado', 'detalleOlimpista.colegio.provincia.departamento'])
            ->where('ci_persona', $cedula)
            ->first();

        if (!$persona) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        $response = [
            'ci_persona' => $persona->ci_persona,
            'nombres' => $persona->nombres,
            'apellidos' => $persona->apellidos,
            'fecha_nacimiento' => $persona->fecha_nacimiento,
            'correo_electronico' => $persona->correo_electronico,
            'celular' => $persona->celular,
            'ci_tutor_legal' => $persona->detalleOlimpista->ci_tutor_legal ?? null,
            'id_departamento' => $persona->detalleOlimpista->colegio->provincia->id_departamento ?? null,
            'id_provincia' => $persona->detalleOlimpista->colegio->id_provincia ?? null,
            'id_colegio' => $persona->detalleOlimpista->unidad_educativa ?? null,
            'id_grado' => $persona->detalleOlimpista->id_grado ?? null,
            'id_olimpiada' => $persona->detalleOlimpista->id_olimpiada ?? null,
        ];

        return response()->json($response);
    }
    public function getByEmail($email): JsonResponse
    {
        $persona = Persona::where('correo_electronico', $email)->first();

        return $persona
            ? response()->json($persona)
            : response()->json(['message' => 'No encontrado'], 404);
    }
}
