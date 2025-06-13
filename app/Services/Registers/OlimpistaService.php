<?php

namespace App\Services\Registers;

use App\Modules\Persons\Models\Person;
use App\Modules\Persons\Models\OlympistDetail;
use Illuminate\Support\Facades\DB;

class OlimpistaService
{
    public function register(array $data): Persona
    {
        return DB::transaction(function () use ($data) {
            // if (Persona::where('ci_persona', $data['cedula_identidad'])->exists()) {
            //     throw new \Exception('La cédula de identidad ya está registrada en el sistema.', 409);
            // }
            // if(DetalleOlimpista::where('ci_olimpista',$data['ci_tutor'])->exists()){
            //     throw new \Exception('Cédula de identidad no válido, otro olimpista no puede ser tutor legal', 409);
            // }
            


            // 1. Guardar al olimpista
            $persona = new Persona();
            $persona->ci_persona = $data['cedula_identidad'];
            $persona->nombres = $data['nombres'];
            $persona->apellidos = $data['apellidos'];
            $persona->correo_electronico = $data['correo_electronico'];
            $persona->fecha_nacimiento = $data['fecha_nacimiento'];
            $persona->celular = $data['celular'] ?? null;
            $persona->save();

            $ciTutor = $data['ci_tutor'];
            // $ciPersona = (string) $data['cedula_identidad'];
            if (!$persona->save()) {
                throw new \Exception('No se pudo guardar al Olimpista.', 500);
            }

            DetalleOlimpista::create([
                'id_olimpiada' => $data['id_olimpiada'] ?? 1,
                'ci_olimpista' => $persona->ci_persona,
                'id_grado' => $data['id_grado'],
                'unidad_educativa' => $data['unidad_educativa'],
                'ci_tutor_legal' => $ciTutor
            ]);

            return $persona;
        });
    }
}
