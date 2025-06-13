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
            GradesSeeder::class,
            OlympiadsSeeder::class,
            DepartmentsSeeder::class,
            ProvincesSeeder::class,
            AreasSeeder::class,
            SchoolsSeeder::class,
            CategoryLevelsSeeder::class,
            LevelGradesSeeder::class,
            PersonsSeeder::class,
            OlympiadAreaLevelsSeeder::class,
            OlympistDetailsSeeder::class,
            UserSeeder::class,
            //InscripcionSeeder::class,
            //PagoSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
