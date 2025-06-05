<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeLevelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('grade_level')->insert([
            ['id_level' => 1, 'id_grade' => 1, 'id_olympiad' => null],
            ['id_level' => 2, 'id_grade' => 2, 'id_olympiad' => null],
            ['id_level' => 3, 'id_grade' => 3, 'id_olympiad' => null],
            ['id_level' => 4, 'id_grade' => 4, 'id_olympiad' => null],
            ['id_level' => 5, 'id_grade' => 5, 'id_olympiad' => null],
            ['id_level' => 6, 'id_grade' => 6, 'id_olympiad' => null],
            ['id_level' => 7, 'id_grade' => 7, 'id_olympiad' => null],
            ['id_level' => 8, 'id_grade' => 8, 'id_olympiad' => null],
            ['id_level' => 9, 'id_grade' => 9, 'id_olympiad' => null],
            ['id_level' => 10, 'id_grade' => 10, 'id_olympiad' => null],
            ['id_level' => 11, 'id_grade' => 11, 'id_olympiad' => null],
            ['id_level' => 12, 'id_grade' => 12, 'id_olympiad' => null],

            ['id_level' => 13, 'id_grade' => 5, 'id_olympiad' => 1],
            ['id_level' => 13, 'id_grade' => 6, 'id_olympiad' => 1],
            
            ['id_level' => 14, 'id_grade' => 7, 'id_olympiad' => 1],
            ['id_level' => 14, 'id_grade' => 8, 'id_olympiad' => 1],
            ['id_level' => 14, 'id_grade' => 9, 'id_olympiad' => 1],

            ['id_level' => 15, 'id_grade' => 7, 'id_olympiad' => 1],
            ['id_level' => 15, 'id_grade' => 8, 'id_olympiad' => 1],
            ['id_level' => 15, 'id_grade' => 9, 'id_olympiad' => 1],

            ['id_level' => 16, 'id_grade' => 10, 'id_olympiad' => 1],
            ['id_level' => 16, 'id_grade' => 11, 'id_olympiad' => 1],
            ['id_level' => 16, 'id_grade' => 12, 'id_olympiad' => 1],

            ['id_level' => 17, 'id_grade' => 7, 'id_olympiad' => 1],
            ['id_level' => 17, 'id_grade' => 8, 'id_olympiad' => 1],
            ['id_level' => 17, 'id_grade' => 9, 'id_olympiad' => 1],

            ['id_level' => 18, 'id_grade' => 10, 'id_olympiad' => 1],
            ['id_level' => 18, 'id_grade' => 11, 'id_olympiad' => 1],
            ['id_level' => 18, 'id_grade' => 12, 'id_olympiad' => 1],

            ['id_level' => 19, 'id_grade' => 7, 'id_olympiad' => 1],
            ['id_level' => 20, 'id_grade' => 8, 'id_olympiad' => 1],
            ['id_level' => 21, 'id_grade' => 9, 'id_olympiad' => 1],
            ['id_level' => 22, 'id_grade' => 10, 'id_olympiad' => 1],
            ['id_level' => 23, 'id_grade' => 11, 'id_olympiad' => 1],
            ['id_level' => 24, 'id_grade' => 12, 'id_olympiad' => 1],

            ['id_level' => 25, 'id_grade' => 5, 'id_olympiad' => 1],
            ['id_level' => 25, 'id_grade' => 6, 'id_olympiad' => 1],

            ['id_level' => 26, 'id_grade' => 7, 'id_olympiad' => 1],
            ['id_level' => 26, 'id_grade' => 8, 'id_olympiad' => 1],
            ['id_level' => 26, 'id_grade' => 9, 'id_olympiad' => 1],
            ['id_level' => 26, 'id_grade' => 10, 'id_olympiad' => 1],
            ['id_level' => 26, 'id_grade' => 11, 'id_olympiad' => 1],
            ['id_level' => 26, 'id_grade' => 12, 'id_olympiad' => 1],

            ['id_level' => 27, 'id_grade' => 5, 'id_olympiad' => 1],
            ['id_level' => 27, 'id_grade' => 6, 'id_olympiad' => 1],

            ['id_level' => 28, 'id_grade' => 7, 'id_olympiad' => 1],
            ['id_level' => 28, 'id_grade' => 8, 'id_olympiad' => 1],
            ['id_level' => 28, 'id_grade' => 9, 'id_olympiad' => 1],
            ['id_level' => 28, 'id_grade' => 10, 'id_olympiad' => 1],
            ['id_level' => 28, 'id_grade' => 11, 'id_olympiad' => 1],
            ['id_level' => 28, 'id_grade' => 12, 'id_olympiad' => 1],
        ]);
    }
}
