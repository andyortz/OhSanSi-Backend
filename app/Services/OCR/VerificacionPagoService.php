<?php

namespace App\Services\OCR;

use App\Models\ListaInscripcion;
use App\Models\Pago;

class VerificacionPagoService
{
    /**
     * Verifica si el pago extraído del OCR coincide con la base de datos.
     *
     * @param array $ocrData
     * @return array
     */
    public function verificarPagoOCR(array $ocrData): array
    {
        $ci = $ocrData['documento'] ?? null;
        $monto = $ocrData['importe_total'] ?? null;

        if (!$ci || !$monto) {
            return [
                'verificado' => false,
                'mensaje' => 'Faltan campos clave para validar el pago.',
                'ci' => $ci,
                'monto_ocr' => $monto
            ];
        }

        $lista = ListaInscripcion::where('ci_responsable_inscripcion', $ci)->first();

        if (!$lista) {
            return [
                'verificado' => false,
                'mensaje' => 'No se encontró ninguna lista de inscripción asociada a este CI.',
                'ci' => $ci
            ];
        }

        $pagos = Pago::where('id_lista', $lista->id_lista)->get();

        foreach ($pagos as $pago) {
            if (floatval($pago->monto_total) == floatval($monto)) {

                if ($pago->verificado) {
                    return [
                        'verificado' => true,
                        'mensaje' => 'El pago ya fue verificado previamente.',
                        'id_lista' => $lista->id_lista,
                        'id_pago' => $pago->id_pago,
                        'monto' => floatval($pago->monto_total)
                    ];
                }

                $pago->verificado = true;
                $pago->verificado_en = now();
                $pago->verificado_por = auth()->user()->name ?? 'sistema';
                $pago->save();

                return [
                    'verificado' => true,
                    'mensaje' => 'El monto del pago coincide con el registro y fue verificado.',
                    'id_lista' => $lista->id_lista,
                    'id_pago' => $pago->id_pago,
                    'monto' => floatval($pago->monto_total)
                ];
            }
        }

        return [
            'verificado' => false,
            'mensaje' => 'No se encontró un pago con el monto indicado.',
            'id_lista' => $lista->id_lista,
            'monto_esperado' => $monto,
            'pagos_registrados' => $pagos->pluck('monto_total')->map(fn($m) => floatval($m))
        ];
    }

}
