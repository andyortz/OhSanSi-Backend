<?php

namespace App\Services\Registers;

use App\Modules\Enrollments\Models\EnrollmentList;
use Illuminate\Support\Facades\DB;

class EnrollmentListService
{
    public function createList(int $enrollment_responsible_ci, int $olympiad_id): EnrollmentList
    {
        return DB::transaction(function () use ($enrollment_responsible_ci, $olympiad_id) {
            // Buscar si ya existe una lista con el mismo responsable y olimpiada
            

            // Crear una nueva lista
            return EnrollmentList::create([
                'enrollment_responsible_ci' => $enrollment_responsible_ci,
                'olympiad_id'               => $olympiad_id,
                'status'                     => 'PENDIENTE',
                'list_creation_date'       => now(),
            ]);
        });
    }
}
