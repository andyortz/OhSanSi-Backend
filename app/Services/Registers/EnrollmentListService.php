<?php

namespace App\Services\Registers;

use App\Modules\Olympiad\Models\EnrollmentList;
use Illuminate\Support\Facades\DB;

class EnrollmentListService
{
    public function createList(int $ci_enrollment_responsible, int $id_olympiad): EnrollmentList
    {
        return DB::transaction(function () use ($ci_enrollment_responsible, $id_olympiad) {
            // Buscar si ya existe una lista con el mismo responsable y olimpiada
            return EnrollmentList::create([
                'ci_enrollment_responsible' => $ci_enrollment_responsible,
                'id_olympiad'               => $id_olympiad,
                'status'                     => 'PENDIENTE',
                'list_creation_date'       => now(),
            ]);

            // Crear una nueva lista
            return EnrollmentList::create([
                'ci_enrollment_responsible' => $ci_enrollment_responsible,
                'id_olympiad'               => $id_olympiad,
                'status'                     => 'PENDIENTE',
                'list_creation_date'       => now(),
            ]);
        });
    }
}
