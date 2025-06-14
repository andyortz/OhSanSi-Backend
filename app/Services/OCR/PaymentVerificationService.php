<?php

namespace App\Services\OCR;

use App\Modules\Enrollments\Models\EnrollmentList;
use App\Modules\Enrollments\Models\Payment;

class PaymentVerificationService
{
    public function verifyPaymentOCR(array $ocrData): array
    {
        $voucher = $ocrData['concept'] ?? null;
        $ci = $ocrData['document'] ?? null;
        $amount = $ocrData['total_import'] ?? null;
        $idListRequested = $ocrData['list_id'] ?? null;

        if (!$voucher || !$ci || !$amount || !$idListRequested) {
            return [
                'verified' => false,
                'massage' => 'Faltan datos para verificar: N° de comprobante y/o ci documento.',
                'missing' => [
                    'voucher' => $voucher ? null : 'No proporcionado',
                    'document' => $ci ? null : 'No proporcionado',
                    'amount' => $amount ? null : 'No proporcionado',
                    'list_id' => $idListRequested ? null : 'No proporcionado'
                ]
            ];
        }

        $payment = Payment::where('voucher', $voucher)
                    ->where('total_amount', $amount)
                    ->first();

        if (!$payment) {
            return [
                'verified' => false,
                'message' => 'No se encontró ningún pago con ese comprobante y monto.',
                'voucher' => $voucher,
                'amount' => $amount
            ];
        }

        if ($payment->id_lista != $idListRequested) {
            return [
                'verified' => false,
                'message' => 'El comprobante no corresponde a la inscripción actual seleccionada.',
                'waiting_list_id' => $payment->list_id,
                'received_list_id' => $idListRequested
            ];
        }

        if ($payment->verified) {
            return [
                'verified' => true,
                'message' => 'El pago ya había sido verificado previamente.',
                'list_id' => $payment->list_id,
                'payment_id' => $payment->payment_id,
                'amount' => floatval($payment->total_amount),
                'voucher' => $payment->voucher
            ];
        }

        $payment->verified = true;
        $payment->verified_in = now();
        $payment->verified_by = auth()->user()->name ?? 'sistema';
        $payment->save();

        $list = $payment->enrollmentList ?? EnrollmentList::find($payment->list_id);

        if ($list && $list->status === 'PENDIENTE') {
            $list->status = 'PAGADO';
            $list->save();
        }


        return [
            'verified' => true,
            'message' => 'Pago verificado correctamente.',
            'list_id' => $payment->list_id,
            'payment_id' => $payment->payment_id,
            'amount' => floatval($payment->total_amount),
            'voucher' => $payment->voucher
        ];
    }

}
