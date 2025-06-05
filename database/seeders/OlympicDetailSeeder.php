<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Person;

class OlympicDetailSeeder extends Seeder
{
    public function run()
    {
        $peopleCi = Person::pluck('ci_person')->toArray();
        $details = [
            [
                'id_olympiad' => 1, // Asume que existe olimpiada con id 1
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Alan Turing
                'id_grade' => 11, // Asume que existe grado con id 11
                'id_school' => 1, // Asume que existe colegio con id 1
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Ada Lovelace como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Ada Lovelace
                'id_grade' => 10,
                'id_school' => 2,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Alan Turing como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Linus Torvalds
                'id_grade' => 9,
                'id_school' => 3,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Tim Berners-Lee como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Tim Berners-Lee
                'id_grade' => 8,
                'id_school' => 4,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)]// Linus Torvalds como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Grace Hopper
                'id_grade' => 12,
                'id_school' => 5,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Bill Gates como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Bill Gates
                'id_grade' => 7,
                'id_school' => 6,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Grace Hopper como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Steve Wozniak
                'id_grade' => 6,
                'id_school' => 7,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Margaret Hamilton como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Margaret Hamilton
                'id_grade' => 5,
                'id_school' => 8,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Steve Wozniak como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Dennis Ritchie
                'id_grade' => 4,
                'id_school' => 9,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Guido van Rossum como tutor
            ],
            [
                'id_olympiad' => 1,
                'ci_olympic' => $peopleCi[array_rand($peopleCi)], // Guido van Rossum
                'id_grade' => 3,
                'id_school' => 10,
                'ci_legal_guardian' => $peopleCi[array_rand($peopleCi)] // Dennis Ritchie como tutor
            ]
        ];

        DB::table('olympic_detail')->insert($details);
    }
}