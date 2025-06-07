<?php

namespace App\Services\OCR;


use App\Modules\Olympist\Models\EnrollmentList;
use App\Models\Pago;
use App\Modules\Olympist\Models\Payment;

class PaymentVerificationService
{
    public function verifyPaymentOCR(array $ocrData): array
    {
        $receipt = $ocrData['clarification'] ?? null;
        $ci = $ocrData['document'] ?? null;
        $amount = $ocrData['total_amount'] ?? null;
        $requested_list_id = $ocrData['id_list'] ?? null;

        if (!$receipt || !$ci || !$amount || !$requested_list_id) {
            return [
                'verified' => false,
                'message' => 'Faltan datos para verificar: comprobante, documento, monto o id_lista.',
                'missing' => [
                    'receipt' => $receipt ? null : 'No proporcionado',
                    'document' => $ci ? null : 'No proporcionado',
                    'amount' => $amount ? null : 'No proporcionado',
                    'id_list' => $requested_list_id ? null : 'No proporcionado'
                ]
            ];
        }

        $payment = Payment::where('receipt', $receipt)
                    ->where('total_amount', $amount)
                    ->first();

        if (!$payment) {
            return [
                'verified' => false,
                'message' => 'No se encontró ningún pago con ese comprobante y monto.',
                'receipt' => $receipt,
                'amount' => $amount
            ];
        }

        if ($payment->id_list != $requested_list_id) {
            return [
                'verified' => false,
                'message' => 'El comprobante no corresponde a la inscripción actual seleccionada.',
                'id_waiting_list' => $payment->id_lista,
                'received_list_id' => $requested_list_id
            ];
        }

        if ($payment->verified) {
            return [
                'verified' => true,
                'message' => 'El pago ya había sido verified previamente.',
                'id_lista' => $payment->id_list,
                'id_pago' => $payment->id_payment,
                'amount' => floatval($payment->total_amount),
                'receipt' => $payment->receipt
            ];
        }

        $payment->verified = true;
        $payment->verified_at = now();
        $payment->verified_by = auth()->user()->name ?? 'sistema';
        $payment->save();

        $list = $payment->id_list ?? EnrollmentList::find($payment->id_list);

        if ($list && $list->status === 'PENDIENTE') {
            $list->status = 'PAGADO';
            $list->save();
        }


        return [
            'verified' => true,
            'message' => 'Pago verificado correctamente.',
            'id_list' => $payment->id_list,
            'id_payment' => $payment->id_payment,
            'amount' => floatval($payment->total_amount),
            'receipt' => $payment->receipt
        ];
    }

}
