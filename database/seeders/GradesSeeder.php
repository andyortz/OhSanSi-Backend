<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradesSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['grade_name' => '1ro Primaria'],
            ['grade_name' => '2do Primaria'],
            ['grade_name' => '3ro Primaria'],
            ['grade_name' => '4to Primaria'],
            ['grade_name' => '5to Primaria'],
            ['grade_name' => '6to Primaria'],
            ['grade_name' => '1ro Secundaria'],
            ['grade_name' => '2do Secundaria'],
            ['grade_name' => '3ro Secundaria'],
            ['grade_name' => '4to Secundaria'],
            ['grade_name' => '5to Secundaria'],
            ['grade_name' => '6to Secundaria'],
        ];

        DB::table('grades')->insert($grades);
    }

}


