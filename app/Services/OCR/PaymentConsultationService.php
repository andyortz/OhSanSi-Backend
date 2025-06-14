<?php

namespace App\Services\OCR;

use App\Modules\Enrollments\Models\EnrollmentList;
use App\Modules\Enrollments\Models\Payment;

class PaymentConsultationService
{
    /**
     * Verifica si un CI tiene al menos un pago no verificado.
     *
     * @param string $ci
     * @return array [existe => bool, mensaje => string]
     */
    public function hasPayments(string $ci): array
    {
        // Obtener todas las listas asociadas al CI
        $lists = EnrollmentList::where('enrollment_responsible_ci', $ci)->pluck('list_id');

        if ($lists->isEmpty()) {
            return [
                'exists' => false,
                'message' => 'No se encontró una lista de inscripción con ese CI.'
            ];
        }

        // Verificar si hay al menos un pago no verificado en cualquiera de esas listas
        $unverifiedPaymentExists = Payment::whereIn('list_id', $lists)
            ->where('verified', false)
            ->exists();

        if (!$unverifiedPaymentExists) {
            return [
                'exists' => false,
                'message' => 'No tiene ningún comprobante generado o ya ha pagado todos.'
            ];
        }

        return [
            'exists' => true,
            'message' => 'El CI tiene al menos un pago pendiente de verificación.'
        ];
    }

}
