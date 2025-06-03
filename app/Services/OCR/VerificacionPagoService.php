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

        // Obtener todas las listas asociadas al CI del responsable
        $listas = ListaInscripcion::where('ci_responsable_inscripcion', $ci)->pluck('id_lista');

        if ($listas->isEmpty()) {
            return [
                'verificado' => false,
                'mensaje' => 'El CI proporcionado no pertenece a ninguna lista de inscripción.',
                'ci' => $ci
            ];
        }

        // Buscar el pago que coincida con cualquiera de las listas, el comprobante y el monto
        $pago = Pago::whereIn('id_lista', $listas)
                ->where('comprobante', $comprobante)
                ->where('monto_total', $monto)
                ->first();

        if (!$pago) {
            $errores = [];

            $comprobanteOk = Pago::whereIn('id_lista', $listas)
                                ->where('comprobante', $comprobante)
                                ->exists();
            $montoOk = Pago::whereIn('id_lista', $listas)
                            ->where('monto_total', $monto)
                            ->exists();

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

        // Marcar el pago como verificado
        $pago->verificado = true;
        $pago->verificado_en = now();
        $pago->verificado_por = auth()->user()->name ?? 'sistema';
        $pago->save();

        // Actualizar el estado de la lista si corresponde
        $lista = $pago->listaInscripcion ?? ListaInscripcion::find($pago->id_lista);

        if ($lista && $lista->estado === 'PENDIENTE') {
            $lista->estado = 'PAGADO';
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
