<?php

namespace App\Services\Registers;

use App\Models\Persona;
use App\Models\DetalleOlimpista;
use Illuminate\Support\Facades\DB;

class OlimpistaService
{
    public function register(array $data): Persona
    {
        return DB::transaction(function () use ($data) {
            if (Persona::where('ci_persona', $data['cedula_identidad'])->exists()) {
                throw new \Exception('La cédula de identidad ya está registrada en el sistema.', 409);
            }

            if (Persona::where('correo_electronico', $data['correo_electronico'])->exists()) {
                throw new \Exception('El correo electrónico ya está registrado en el sistema.', 409);
            }

            $persona = new Persona();
            $persona->ci_persona = $data['cedula_identidad'];
            $persona->nombres = $data['nombres'];
            $persona->apellidos = $data['apellidos'];
            $persona->correo_electronico = $data['correo_electronico'];
            $persona->fecha_nacimiento = $data['fecha_nacimiento'];
            $persona->celular = null;
            $persona->save();

            DetalleOlimpista::create([
                'id_olimpiada' => $data['id_olimpiada'] ?? 1,
                'ci_olimpista' => $persona->ci_persona,
                'id_grado' => $data['id_grado'],
                'unidad_educativa' => $data['unidad_educativa'],
                'ci_tutor_legal' => $data['ci_tutor'],
            ]);

            return $persona;
        });
    }
}
