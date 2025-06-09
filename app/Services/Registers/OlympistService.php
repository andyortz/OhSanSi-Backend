<?php

namespace App\Services\Registers;

use App\Modules\Olympist\Models\Person;
use App\Modules\Olympist\Models\OlympistDetail;
use Illuminate\Support\Facades\DB;

class OlympistService
{
    public function register(array $data): Person
    {
        return DB::transaction(function () use ($data) {
            // 1. Guardar al olimpista
            $person = new Person();
            $person->ci_person = $data['ci'];
            $person->names = $data['names'];
            $person->surnames = $data['surnames'];
            $person->email = $data['email'];
            $person->birthdate = $data['birthdate'];
            $person->phone = $data['phone'] ?? null;
            $person->save();

            $ciTutor = $data['ci_tutor'];
            // $ciPersona = (string) $data['cedula_identidad'];
            if (!$person->save()) {
                throw new \Exception('No se pudo guardar al Olimpista.', 500);
            }

            OlympistDetail::create([
                'id_olympiad' => $data['id_olympiad'] ?? 1,
                'ci_olympic' => $person->ci_person,
                'id_grade' => $data['id_grade'],
                'id_school' => $data['school'],
                'ci_legal_guardian' => $ciTutor
            ]);

            return $person;
        });
    }
}
