<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Enrollments\Models\Enrollment;
use App\Modules\Enrollments\Models\Pago;
use Illuminate\Support\Facades\DB;

class EnrollmentsSeeder extends Seeder
{
    public function run()
    {
        $pagos = Pago::inRandomOrder()->take(5)->pluck('id_pago');

        foreach ($pagos as $idPago) {
            Inscripcion::create([
                'olympiad_id' => 1, 
                'olympist_detail_id' => rand(5, 10), 
                'academic_tutor_ci' => rand(1, 5), 
                'payment_id' => $idPago,
                'level_id' => rand(1, 12), 
                'status' => 'inscrito', 
                'registration_date' => now()->subDays(rand(1, 20)),
            ]);
        }
    }
}
