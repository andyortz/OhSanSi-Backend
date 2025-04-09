<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parentesco;

class ParentescosSeeder extends Seeder
{
    public function run(): void
    {
        Parentesco::create([
            'id_olimpista' => 1,
            'id_tutor' => 1,
        ]);
    }
}
