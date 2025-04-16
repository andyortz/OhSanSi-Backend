<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parentesco;
use Illuminate\Support\Facades\DB;

class ParentescosSeeder extends Seeder
{
    public function run(): void
    {
        
        $parentescos= [
            ['id_olimpista' => 1, 'id_tutor' => 1, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 2, 'id_tutor' => 2, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 3, 'id_tutor' => 3, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 4, 'id_tutor' => 4, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 5, 'id_tutor' => 5, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 6, 'id_tutor' => 6, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 7, 'id_tutor' => 7, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 8, 'id_tutor' => 8, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 9, 'id_tutor' => 9, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 10, 'id_tutor' => 1, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 11, 'id_tutor' => 2, 'rol_parentesco' => 'Tutor Legal'],
            ['id_olimpista' => 1, 'id_tutor' => 3, 'rol_parentesco' => 'Tutor Academico'],
            ['id_olimpista' => 3, 'id_tutor' => 5, 'rol_parentesco' => 'Tutor Academico'],
            ['id_olimpista' => 5, 'id_tutor' => 7, 'rol_parentesco' => 'Tutor Academico'],
            ['id_olimpista' => 7, 'id_tutor' => 9, 'rol_parentesco' => 'Tutor Academico'],
            ['id_olimpista' => 9, 'id_tutor' => 2, 'rol_parentesco' => 'Tutor Academico'],
            ['id_olimpista' => 11, 'id_tutor' => 4, 'rol_parentesco' => 'Tutor Academico']
        ];
        DB::table('parentescos')->insert($parentescos);
    }
}
