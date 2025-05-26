<?php

namespace App\Services\OCR;

use App\Models\Pago;

class VerificacionPagoService
{
    public function verificarPagoOCR(array $ocrData): array
    {
        $comprobante = $ocrData['aclaracion'] ?? null;

        if (!$comprobante) {
            return [
                'verificado' => false,
                'mensaje' => 'No se detectó el comprobante de pago (PAGO-...).',
            ];
        }

        $pago = Pago::where('comprobante', $comprobante)->first();

        if (!$pago) {
            return [
                'verificado' => false,
                'mensaje' => 'No se encontró ningún pago registrado con el comprobante proporcionado.',
                'comprobante_ocr' => $comprobante
            ];
        }

        if ($pago->verificado) {
            return [
                'verificado' => true,
                'mensaje' => 'El pago ya fue verificado previamente.',
                'id_lista' => $pago->id_lista,
                'id_pago' => $pago->id_pago,
                'monto' => floatval($pago->monto_total),
                'comprobante' => $pago->comprobante
            ];
        }

        $pago->verificado = true;
        $pago->verificado_en = now();
        $pago->verificado_por = auth()->user()->name ?? 'sistema';
        $pago->save();

        return [
            'verificado' => true,
            'mensaje' => 'El pago fue verificado correctamente por comprobante.',
            'id_lista' => $pago->id_lista,
            'id_pago' => $pago->id_pago,
            'monto' => floatval($pago->monto_total),
            'comprobante' => $pago->comprobante
        ];
    }
}
