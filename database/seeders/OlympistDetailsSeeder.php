<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Modules\Persons\Models\Person;

class OlympistDetailsSeeder extends Seeder
{
    public function run()
    {
        $ciPersons = Person::pluck('person_ci')->toArray();
        $detalles = [
            [
                'olympiad_id' => 1, // Asume que existe olimpiada con id 1
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Alan Turing
                'grade_id' => 11, // Asume que existe grado con id 11
                'school' => 1, // Asume que existe colegio con id 1
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Ada Lovelace como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Ada Lovelace
                'grade_id' => 10,
                'school' => 2,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Alan Turing como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Linus Torvalds
                'grade_id' => 9,
                'school' => 3,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Tim Berners-Lee como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Tim Berners-Lee
                'grade_id' => 8,
                'school' => 4,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)]// Linus Torvalds como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Grace Hopper
                'grade_id' => 12,
                'school' => 5,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Bill Gates como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Bill Gates
                'grade_id' => 7,
                'school' => 6,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Grace Hopper como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Steve Wozniak
                'grade_id' => 6,
                'school' => 7,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Margaret Hamilton como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Margaret Hamilton
                'grade_id' => 5,
                'school' => 8,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Steve Wozniak como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Dennis Ritchie
                'grade_id' => 4,
                'school' => 9,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Guido van Rossum como tutor
            ],
            [
                'olympiad_id' => 1,
                'olympist_ci' => $ciPersons[array_rand($ciPersons)], // Guido van Rossum
                'grade_id' => 3,
                'school' => 10,
                'guardian_legal_ci' => $ciPersons[array_rand($ciPersons)] // Dennis Ritchie como tutor
            ]
        ];

        DB::table('olympist_detail')->insert($detalles);
    }
}