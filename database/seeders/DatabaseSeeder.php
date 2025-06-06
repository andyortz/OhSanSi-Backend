<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GradeSeeder::class,
            OlympiadSeeder::class,
            DepartamentSeeder::class,
            ProvinceSeeder::class,
            AreaSeeder::class,
            SchoolSeeder::class,
            LevelSeeder::class,
            GradeLevelSeeder::class,
            PersonSeeder::class,
            AreaLevelOlympiadSeeder::class,
            OlympistDetailSeeder::class,
        ]);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
