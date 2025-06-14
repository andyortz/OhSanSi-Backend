<?php

namespace App\Services\Registers;

use App\Modules\Persons\Models\OlympistDetail;
use App\Modules\Enrollments\Models\Enrollment;
use App\Modules\Persons\Models\Person;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    protected $enrollmentListService;

    public function __construct(EnrollmentListService $enrollmentListService)
    {
        $this->enrollmentListService = $enrollmentListService;
    }

    public function register(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            $detail = OlympistDetail::where('olympist_ci', $data['ci'])->first();
            if (!$detail) {
                throw new \Exception('El Olimpista no se encuentra registrado.', 404);
            }

            $academic_tutor_ci = $data['academic_tutor_ci'] ?? null;
            if ($academic_tutor_ci && !Person::where('person_ci', $academic_tutor_ci)->exists()) {
                $academic_tutor_ci = null;
            }

            return Enrollment::create([
                'list_id' => $data['id_lista'], // Usamos el ID ya generado
                'olympist_detail_id' => $detail->id_detalle_olimpista,
                'level_id' => $data['nivel'],
                'academic_tutor_ci' => $academic_tutor_ci,
                'status' => 'PENDIENTE',
                'registration_date' => now(),
            ]);
        });
    }
}
