<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Administrador-OhSansi',
            'email' => 'OhSansi@test.com',
            'password' => bcrypt('OhSansiAdmin2025'),
        ]);
    }
}
