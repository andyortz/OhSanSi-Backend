<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Persona;

class DetalleOlimpistaSeeder extends Seeder
{
    public function run()
    {
        $personasCi = Persona::pluck('ci_persona')->toArray();
        $detalles = [
            [
                'id_olimpiada' => 1, // Asume que existe olimpiada con id 1
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Alan Turing
                'id_grado' => 11, // Asume que existe grado con id 11
                'unidad_educativa' => 1, // Asume que existe colegio con id 1
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Ada Lovelace como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Ada Lovelace
                'id_grado' => 10,
                'unidad_educativa' => 2,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Alan Turing como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Linus Torvalds
                'id_grado' => 9,
                'unidad_educativa' => 3,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Tim Berners-Lee como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Tim Berners-Lee
                'id_grado' => 8,
                'unidad_educativa' => 4,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)]// Linus Torvalds como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Grace Hopper
                'id_grado' => 12,
                'unidad_educativa' => 5,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Bill Gates como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Bill Gates
                'id_grado' => 7,
                'unidad_educativa' => 6,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Grace Hopper como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Steve Wozniak
                'id_grado' => 6,
                'unidad_educativa' => 7,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Margaret Hamilton como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Margaret Hamilton
                'id_grado' => 5,
                'unidad_educativa' => 8,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Steve Wozniak como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Dennis Ritchie
                'id_grado' => 4,
                'unidad_educativa' => 9,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Guido van Rossum como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => $personasCi[array_rand($personasCi)], // Guido van Rossum
                'id_grado' => 3,
                'unidad_educativa' => 10,
                'ci_tutor_legal' => $personasCi[array_rand($personasCi)] // Dennis Ritchie como tutor
            ]
        ];

        DB::table('detalle_olimpista')->insert($detalles);
    }
}