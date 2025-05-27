<?php

namespace App\Services\OCR;

use App\Models\ListaInscripcion;
use App\Models\Pago;

class ConsultaPagoService
{
    /**
     * Verifica si un CI tiene al menos un pago NO verificado asociado.
     *
     * @param string $ci
     * @return array [existe => bool, mensaje => string]
     */
    public function tienePagos(string $ci): array
    {
        $lista = ListaInscripcion::where('ci_responsable_inscripcion', $ci)->first();

        if (!$lista) {
            return [
                'existe' => false,
                'mensaje' => 'No se encontró una lista de inscripción con ese CI.'
            ];
        }

        $existePagoNoVerificado = Pago::where('id_lista', $lista->id_lista)
            ->where('verificado', false)
            ->exists();

        if (!$existePagoNoVerificado) {
            return [
                'existe' => false,
                'mensaje' => 'No tiene ningún comprobante generado o ya ha pagado todos.'
            ];
        }

        return [
            'existe' => true,
            'mensaje' => 'El CI tiene al menos un pago pendiente de verificación.'
        ];
    }
}