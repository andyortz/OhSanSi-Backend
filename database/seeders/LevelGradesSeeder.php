<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelGradesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('level_grades')->insert([
            ['level_id' => 1, 'grade_id' => 1, 'olympiad_id' => null],
            ['level_id' => 2, 'grade_id' => 2, 'olympiad_id' => null],
            ['level_id' => 3, 'grade_id' => 3, 'olympiad_id' => null],
            ['level_id' => 4, 'grade_id' => 4, 'olympiad_id' => null],
            ['level_id' => 5, 'grade_id' => 5, 'olympiad_id' => null],
            ['level_id' => 6, 'grade_id' => 6, 'olympiad_id' => null],
            ['level_id' => 7, 'grade_id' => 7, 'olympiad_id' => null],
            ['level_id' => 8, 'grade_id' => 8, 'olympiad_id' => null],
            ['level_id' => 9, 'grade_id' => 9, 'olympiad_id' => null],
            ['level_id' => 10, 'grade_id' => 10, 'olympiad_id' => null],
            ['level_id' => 11, 'grade_id' => 11, 'olympiad_id' => null],
            ['level_id' => 12, 'grade_id' => 12, 'olympiad_id' => null],

            ['level_id' => 13, 'grade_id' => 5, 'olympiad_id' => 1],
            ['level_id' => 13, 'grade_id' => 6, 'olympiad_id' => 1],
            
            ['level_id' => 14, 'grade_id' => 7, 'olympiad_id' => 1],
            ['level_id' => 14, 'grade_id' => 8, 'olympiad_id' => 1],
            ['level_id' => 14, 'grade_id' => 9, 'olympiad_id' => 1],

            ['level_id' => 15, 'grade_id' => 7, 'olympiad_id' => 1],
            ['level_id' => 15, 'grade_id' => 8, 'olympiad_id' => 1],
            ['level_id' => 15, 'grade_id' => 9, 'olympiad_id' => 1],

            ['level_id' => 16, 'grade_id' => 10, 'olympiad_id' => 1],
            ['level_id' => 16, 'grade_id' => 11, 'olympiad_id' => 1],
            ['level_id' => 16, 'grade_id' => 12, 'olympiad_id' => 1],

            ['level_id' => 17, 'grade_id' => 7, 'olympiad_id' => 1],
            ['level_id' => 17, 'grade_id' => 8, 'olympiad_id' => 1],
            ['level_id' => 17, 'grade_id' => 9, 'olympiad_id' => 1],

            ['level_id' => 18, 'grade_id' => 10, 'olympiad_id' => 1],
            ['level_id' => 18, 'grade_id' => 11, 'olympiad_id' => 1],
            ['level_id' => 18, 'grade_id' => 12, 'olympiad_id' => 1],

            ['level_id' => 19, 'grade_id' => 7, 'olympiad_id' => 1],
            ['level_id' => 20, 'grade_id' => 8, 'olympiad_id' => 1],
            ['level_id' => 21, 'grade_id' => 9, 'olympiad_id' => 1],
            ['level_id' => 22, 'grade_id' => 10, 'olympiad_id' => 1],
            ['level_id' => 23, 'grade_id' => 11, 'olympiad_id' => 1],
            ['level_id' => 24, 'grade_id' => 12, 'olympiad_id' => 1],

            ['level_id' => 25, 'grade_id' => 5, 'olympiad_id' => 1],
            ['level_id' => 25, 'grade_id' => 6, 'olympiad_id' => 1],

            ['level_id' => 26, 'grade_id' => 7, 'olympiad_id' => 1],
            ['level_id' => 26, 'grade_id' => 8, 'olympiad_id' => 1],
            ['level_id' => 26, 'grade_id' => 9, 'olympiad_id' => 1],
            ['level_id' => 26, 'grade_id' => 10, 'olympiad_id' => 1],
            ['level_id' => 26, 'grade_id' => 11, 'olympiad_id' => 1],
            ['level_id' => 26, 'grade_id' => 12, 'olympiad_id' => 1],

            ['level_id' => 27, 'grade_id' => 5, 'olympiad_id' => 1],
            ['level_id' => 27, 'grade_id' => 6, 'olympiad_id' => 1],

            ['level_id' => 28, 'grade_id' => 7, 'olympiad_id' => 1],
            ['level_id' => 28, 'grade_id' => 8, 'olympiad_id' => 1],
            ['level_id' => 28, 'grade_id' => 9, 'olympiad_id' => 1],
            ['level_id' => 28, 'grade_id' => 10, 'olympiad_id' => 1],
            ['level_id' => 28, 'grade_id' => 11, 'olympiad_id' => 1],
            ['level_id' => 28, 'grade_id' => 12, 'olympiad_id' => 1],
        ]);
    }
}
