<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OlympiadsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('olympiad')->insert([
            [
                'year' => 2025,
                'cost' => 15.00,
                'start_date' => '2025-03-17',
                'end_date' => '2025-08-27',
                'created_in' => Carbon::now(),
                'max_categories_per_olympist' => 2,
                'olympiad_name' => 'INVIERNO'
            ],
        ]);
    }
}
