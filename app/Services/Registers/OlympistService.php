<?php

namespace App\Services\Registers;

use App\Modules\Persons\Models\Person;
use App\Modules\Persons\Models\OlympistDetail;
use Illuminate\Support\Facades\DB;

class OlympistService
{
    public function register(array $data): Person
    {
        return DB::transaction(function () use ($data) {
            // if (Persona::where('ci_persona', $data['cedula_identidad'])->exists()) {
            //     throw new \Exception('La cédula de identidad ya está registrada en el sistema.', 409);
            // }
            // if(DetalleOlimpista::where('ci_olimpista',$data['ci_tutor'])->exists()){
            //     throw new \Exception('Cédula de identidad no válido, otro olimpista no puede ser tutor legal', 409);
            // }
            


            // 1. Guardar al olimpista
            $person = new Person();
            $person->person_ci = $data['olympist_ci'];
            $person->names = $data['names'];
            $person->surnames = $data['surnames'];
            $person->email = $data['email'];
            $person->birthdate = $data['birthdate'];
            $person->phone = $data['phone'] ?? null;
            $person->save();

            $tutorCi = $data['tutor_ci'];
            // $ciPersona = (string) $data['cedula_identidad'];
            if (!$person->save()) {
                throw new \Exception('No se pudo guardar al Olimpista.', 500);
            }

            OlympistDetail::create([
                'olympiad_id' => $data['olympiad_id'] ?? 1,
                'olympist_ci' => $person->person_ci,
                'grade_id' => $data['grade_id'],
                'school' => $data['school'],
                'guardian_legal_ci' => $tutorCi
            ]);

            return $person;
        });
    }
}
