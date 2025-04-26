<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DetalleOlimpistaSeeder extends Seeder
{
    public function run()
    {
        $detalles = [
            [
                'id_olimpiada' => 1, // Asume que existe olimpiada con id 1
                'ci_olimpista' => 1, // Alan Turing
                'id_grado' => 11, // Asume que existe grado con id 11
                'unidad_educativa' => 1, // Asume que existe colegio con id 1
                'ci_tutor_legal' => 2 // Ada Lovelace como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 2, // Ada Lovelace
                'id_grado' => 10,
                'unidad_educativa' => 2,
                'ci_tutor_legal' => 1 // Alan Turing como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 3, // Linus Torvalds
                'id_grado' => 9,
                'unidad_educativa' => 3,
                'ci_tutor_legal' => 4 // Tim Berners-Lee como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 4, // Tim Berners-Lee
                'id_grado' => 8,
                'unidad_educativa' => 4,
                'ci_tutor_legal' => 3 // Linus Torvalds como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 5, // Grace Hopper
                'id_grado' => 12,
                'unidad_educativa' => 5,
                'ci_tutor_legal' => 6 // Bill Gates como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 6, // Bill Gates
                'id_grado' => 7,
                'unidad_educativa' => 6,
                'ci_tutor_legal' => 5 // Grace Hopper como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 7, // Steve Wozniak
                'id_grado' => 6,
                'unidad_educativa' => 7,
                'ci_tutor_legal' => 8 // Margaret Hamilton como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 8, // Margaret Hamilton
                'id_grado' => 5,
                'unidad_educativa' => 8,
                'ci_tutor_legal' => 7 // Steve Wozniak como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 9, // Dennis Ritchie
                'id_grado' => 4,
                'unidad_educativa' => 9,
                'ci_tutor_legal' => 10 // Guido van Rossum como tutor
            ],
            [
                'id_olimpiada' => 1,
                'ci_olimpista' => 10, // Guido van Rossum
                'id_grado' => 3,
                'unidad_educativa' => 10,
                'ci_tutor_legal' => 9 // Dennis Ritchie como tutor
            ]
        ];

        DB::table('detalle_olimpistas')->insert($detalles);
    }
}