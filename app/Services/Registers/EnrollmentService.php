<?php

namespace App\Services\Registers;

use App\Moduels\Olympist\Models\OlympicDetail;
use App\Modules\Olympist\Models\Enrollment;
use App\Modules\Olympist\Models\Person;

use Illuminate\Support\Facades\DB;

class EnrollmentServiceService
{
    protected $enrollmentListService;

    public function __construct(EnrollmentListService $enrollmentListService)
    {
        $this->enrollmentListService = $enrollmentListService;
    }

    public function register(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            $detail = OlympicDetail::where('ci_olympist', $data['ci'])->first();
            if (!$detail) {
                throw new \Exception('El Olimpista no se encuentra registrado.', 404);
            }

            $ci_academic_advisor = $data['ci_academic_advisor'] ?? null;
            if ($ci_academic_advisor && !Person::where('ci_person', $ci_academic_advisor)->exists()) {
                $ci_academic_advisor = null;
            }

            return Enrollment::create([
                'id_list' => $data['id_list'], // Usamos el ID ya generado
                'id_olympic_detail' => $detail->id_olympic_detail,
                'id_level' => $data['level'],
                'ci_academic_advisor' => $ci_academic_advisor,
                'stastus' => 'PENDIENTE',
                'enrollment_date' => now(),
            ]);
        });
    }
}
