<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OCR\ConsultaPagoService;

class PaymentInquiryController extends Controller
{
    protected $paymentInquiry;

    public function __construct(ConsultaPagoService $paymentInquiry)
    {
        $this->paymentInquiry = $paymentInquiry;
    }

    public function checkByCi(string $ci)
    {
        $answer = $this->paymentInquiry->havePayment($ci);

        return response()->json($answer);
    }
}
