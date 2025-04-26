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
            GradosSeeder::class,
            OlimpiadasSeeder::class,
            DepartamentosSeeder::class,
            ProvinciasSeeder::class,
            AreasSeeder::class,
            ColegiosSeeder::class,
            NivelesSeeder::class,
            GradosNivelesSeeder::class,
            PersonasSeeder::class,
            NivelesAreasOlimpiadasSeeder::class,
            DetalleOlimpistaSeeder::class
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
