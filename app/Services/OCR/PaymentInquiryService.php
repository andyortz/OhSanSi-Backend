<?php

namespace App\Services\OCR;


use App\Modules\Olympist\EnrollmentList;
use App\Modules\Olympist\Models\Payment;

class PaymentInquiryService
{
    /**
     * Verifica si un CI tiene al menos un pago no verificado.
     *
     * @param string $ci
     * @return array [existe => bool, mensaje => string]
     */
    public function havePayment(string $ci): array
    {
        //obtain all lists associated with the CI
        $lists = EnrollmentList::where('ci_enrollment_responsible', $ci)->pluck('id_list');

        if ($lists->isEmpty()) {
            return [
                'exists' => false,
                'message' => 'No se encontró una lista de inscripción con ese CI.'
            ];
        }
        //Verify if there is at least one unverified payment in any of those lists
        $existsUnverifiedPayment = Payment::whereIn('id_list', $lists)
            ->where('verified', false)
            ->exists();

        if (!$existsUnverifiedPayment) {
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
