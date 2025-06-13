<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Enrollments\Models\Pago;
use App\Modules\Persons\Models\Person;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        // Obtener algunos ci_persona aleatorios
        $persons = Persona::inRandomOrder()->take(5)->pluck('ci_persona');

        foreach ($persons as $index => $personCi) {
            Pago::create([
                'voucher' => 'COMP-' . strtoupper(uniqid()),
                'payment_date' => now()->subDays(rand(1, 30)),
                'enrollment_responsible_ci' => $personCi,
                'total_amount' => rand(100, 500),
                'verified' => (bool)rand(0, 1),
                'verified_in' => now(),
                'verified_by' => 'admin_user_' . ($index + 1),
            ]);
        }
    }
}
