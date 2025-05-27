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

        if (!$comprobante || !$ci || !$monto) {
            return [
                'verificado' => false,
                'mensaje' => 'Faltan datos para verificar: comprobante, documento o monto.',
                'faltantes' => [
                    'comprobante' => $comprobante ? null : 'No proporcionado',
                    'documento' => $ci ? null : 'No proporcionado',
                    'monto' => $monto ? null : 'No proporcionado'
                ]
            ];
        }

        $lista = ListaInscripcion::where('ci_responsable_inscripcion', $ci)->first();

        if (!$lista) {
            return [
                'verificado' => false,
                'mensaje' => 'El CI proporcionado no pertenece a ninguna lista de inscripción.',
                'ci' => $ci
            ];
        }

        $pago = Pago::where('id_lista', $lista->id_lista)
                    ->where(function ($q) use ($comprobante) {
                        $q->where('comprobante', $comprobante);
                    })
                    ->where(function ($q) use ($monto) {
                        $q->where('monto_total', $monto);
                    })
                    ->first();

        if (!$pago) {
            $errores = [];

            $comprobanteOk = Pago::where('id_lista', $lista->id_lista)->where('comprobante', $comprobante)->exists();
            $montoOk = Pago::where('id_lista', $lista->id_lista)->where('monto_total', $monto)->exists();

            if (!$comprobanteOk) $errores[] = 'Comprobante incorrecto o no coincide con el CI';
            if (!$montoOk) $errores[] = 'Monto incorrecto o no coincide con el CI';

            return [
                'verificado' => false,
                'mensaje' => 'No se pudo validar el pago.',
                'detalle_errores' => $errores,
                'comprobante' => $comprobante,
                'documento' => $ci,
                'monto' => $monto
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

        $lista = $pago->listaInscripcion ?? ListaInscripcion::find($pago->id_lista);

        if ($lista && $lista->estado === 'PENDIENTE') {
            $lista->estado = 'REGISTRADO';
            $lista->save();
        }

        return [
            'verificado' => true,
            'mensaje' => 'El pago fue verificado correctamente (comprobante, documento y monto coinciden).',
            'id_lista' => $pago->id_lista,
            'id_pago' => $pago->id_pago,
            'monto' => floatval($pago->monto_total),
            'comprobante' => $pago->comprobante
        ];
    }
}
