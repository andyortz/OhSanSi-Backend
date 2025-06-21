<?php

namespace App\Modules\Persons\Services\Registers;

use App\Modules\Persons\Models\Person;
use App\Modules\Persons\Models\OlympistDetail;
use Illuminate\Support\Facades\DB;

class OlympistService
{
    public function register(array $data): Person
    {
        return DB::transaction(function () use ($data) {
            
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
                'school_id' => $data['school'],
                'guardian_legal_ci' => $tutorCi
            ]);

            return $person;
        });
    }
}
