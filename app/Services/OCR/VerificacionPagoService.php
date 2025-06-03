<?php

namespace App\Services\OCR;

use App\Models\ListaInscripcion;
use App\Models\Pago;

class VerificacionPagoService
{
    public function verificarPagoOCR(array $ocrData): array
    {
        $comprobante = $ocrData['aclaracion'] ?? null;
        $ci = $ocrData['documento'] ?? null;
        $monto = $ocrData['importe_total'] ?? null;
        $idListaSolicitado = $ocrData['id_lista'] ?? null;

        if (!$comprobante || !$ci || !$monto || !$idListaSolicitado) {
            return [
                'verificado' => false,
                'mensaje' => 'Faltan datos para verificar: comprobante, documento, monto o id_lista.',
                'faltantes' => [
                    'comprobante' => $comprobante ? null : 'No proporcionado',
                    'documento' => $ci ? null : 'No proporcionado',
                    'monto' => $monto ? null : 'No proporcionado',
                    'id_lista' => $idListaSolicitado ? null : 'No proporcionado'
                ]
            ];
        }

        $pago = Pago::where('comprobante', $comprobante)
                    ->where('monto_total', $monto)
                    ->first();

        if (!$pago) {
            return [
                'verificado' => false,
                'mensaje' => 'No se encontró ningún pago con ese comprobante y monto.',
                'comprobante' => $comprobante,
                'monto' => $monto
            ];
        }

        if ($pago->id_lista != $idListaSolicitado) {
            return [
                'verificado' => false,
                'mensaje' => 'El comprobante no corresponde a la inscripción actual seleccionada.',
                'id_lista_esperada' => $pago->id_lista,
                'id_lista_recibida' => $idListaSolicitado
            ];
        }

        if ($pago->verificado) {
            return [
                'verificado' => true,
                'mensaje' => 'El pago ya había sido verificado previamente.',
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

        $lista = $pago->listaInscripcion;
        if ($lista && $lista->estado === 'PENDIENTE') {
            $lista->estado = 'REGISTRADO';
            $lista->save();
        }

        return [
            'verificado' => true,
            'mensaje' => 'Pago verificado correctamente.',
            'id_lista' => $pago->id_lista,
            'id_pago' => $pago->id_pago,
            'monto' => floatval($pago->monto_total),
            'comprobante' => $pago->comprobante
        ];
    }

}
