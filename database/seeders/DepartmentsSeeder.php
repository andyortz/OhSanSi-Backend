<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['department_id' => 1, 'department_name' => 'Chuquisaca'],
            ['department_id' => 2, 'department_name' => 'La Paz'],
            ['department_id' => 3, 'department_name' => 'Cochabamba'],
            ['department_id' => 4, 'department_name' => 'Oruro'],
            ['department_id' => 5, 'department_name' => 'Potosí'],
            ['department_id' => 6, 'department_name' => 'Tarija'],
            ['department_id' => 7, 'department_name' => 'Santa Cruz'],
            ['department_id' => 8, 'department_name' => 'Beni'],
            ['department_id' => 9, 'department_name' => 'Pando'],
        ];

        DB::table('departments')->insert($departments);
    }
}
