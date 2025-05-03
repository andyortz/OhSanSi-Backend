<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\DetalleOlimpista;
use App\Repositories\OlimpistaRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OlimpistaController extends Controller
{
    protected $repo;
    
    public function __construct(OlimpistaRepository $repo)
    {
        $this->repo = $repo;
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
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nombres' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'cedula_identidad' => 'required|integer',
                'fecha_nacimiento' => 'required|date',
                'correo_electronico' => 'required|email|max:100',
                'unidad_educativa' => 'required|integer',
                'id_grado' => 'required|integer',
                'ci_tutor' => 'required|integer',
            ]);

            // Validar que la cedula_identidad NO exista
            if (Persona::where('ci_persona', $data['cedula_identidad'])->exists()) {
                return response()->json([
                    'message' => 'La cédula de identidad ya está registrada en el sistema.'
                ], 409);
            }

            // if (Persona::where('correo_electronico', $data['correo_electronico'])->exists()) {
            //     return response()->json([
            //         'message' => 'El correo electrónico ya está registrado en el sistema.'
            //     ], 409);
            // }
            //Validación del mismo tutor mismo olimpista 
            
            DB::beginTransaction();
            // Crear persona
            $persona = new Persona();
            $persona->ci_persona = $data['cedula_identidad'];
            $persona->nombres = $data['nombres'];
            $persona->apellidos = $data['apellidos'];
            $persona->correo_electronico = $data['correo_electronico'];
            $persona->fecha_nacimiento = $data['fecha_nacimiento'];
            $persona->celular = null;
            $persona->save();

            // Crear detalle olimpista
            DetalleOlimpista::create([
                'id_olimpiada' => 1, // fijo o pásalo como campo
                'ci_olimpista' => $persona->ci_persona,
                'id_grado' => $data['id_grado'],
                'unidad_educativa' => $data['unidad_educativa'],
                'ci_tutor_legal' => $data['ci_tutor'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Olimpista registrado exitosamente.',
                'persona' => $persona
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }


    public function getByCedula($cedula)
    {
        $persona = Persona::where('ci_persona', $cedula)->first();

        return $persona
            ? response()->json($persona)
            : response()->json(['message' => 'No encontrado'], 404);
    }

    public function getByEmail($email)
    {
        $persona = Persona::where('correo_electronico', $email)->first();

        return $persona
            ? response()->json($persona)
            : response()->json(['message' => 'No encontrado'], 404);
    }
}
