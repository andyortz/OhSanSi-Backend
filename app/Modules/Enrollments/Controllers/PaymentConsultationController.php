<?php

namespace App\Modules\Enrollments\Controllers;

use Illuminate\Http\Request;
use App\Services\OCR\PaymentConsultationService;

class PaymentConsultationController
{
    protected $paymentConsultation;

    public function __construct(PaymentConsultationService $paymentConsultation)
    {
        $this->paymentConsultation = $paymentConsultation;
    }

    public function verificarPorCi(string $ci)
    {
        $answer = $this->paymentConsultation->hasPayments($ci);

        return response()->json($answer);
    }
}
