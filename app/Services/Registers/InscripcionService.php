<?php

namespace App\Services\Registers;

use App\Models\DetalleOlimpista;
use App\Models\Inscripcion;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class InscripcionService
{
    protected $listaInscripcionService;

    public function __construct(ListaInscripcionService $listaInscripcionService)
    {
        $this->listaInscripcionService = $listaInscripcionService;
    }

    public function register(array $data): Inscripcion
    {
        return DB::transaction(function () use ($data) {
            $detalle = DetalleOlimpista::where('ci_olimpista', $data['ci'])->first();
            if (!$detalle) {
                throw new \Exception('El Olimpista no se encuentra registrado.', 404);
            }

            // Validar tutor académico (opcional)
            $ci_tutor_academico = $data['ci_tutor_academico'] ?? null;
            if ($ci_tutor_academico && !Persona::where('ci_persona', $ci_tutor_academico)->exists()){
                $ci_tutor_academico = null;
            }

            // Crear o reutilizar lista
            $lista = $this->listaInscripcionService->crearLista(
                $data['ci_responsable_inscripcion'],
                $detalle->id_olimpiada
            );

            // Crear inscripción
            return Inscripcion::create([
                'id_lista' => $lista->id_lista,
                'id_detalle_olimpista' => $detalle->id_detalle_olimpista,
                'id_nivel' => $data['nivel'],
                'ci_tutor_academico' => $ci_tutor_academico,
                'estado' => strtoupper($data['estado'] ?? 'PENDIENTE'),
                'fecha_inscripcion' => now(),
            ]);
        });
    }
}
