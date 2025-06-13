<?php

namespace App\Services\OCR;

use App\Modules\Enrollments\Models\EnrollmentList;
use App\Modules\Enrollments\Models\Pago;

class ConsultaPagoService
{
    /**
     * Verifica si un CI tiene al menos un pago no verificado.
     *
     * @param string $ci
     * @return array [existe => bool, mensaje => string]
     */
    public function tienePagos(string $ci): array
    {
        // Obtener todas las listas asociadas al CI
        $listas = ListaInscripcion::where('ci_responsable_inscripcion', $ci)->pluck('id_lista');

        if ($listas->isEmpty()) {
            return [
                'existe' => false,
                'mensaje' => 'No se encontró una lista de inscripción con ese CI.'
            ];
        }

        // Verificar si hay al menos un pago no verificado en cualquiera de esas listas
        $existePagoNoVerificado = Pago::whereIn('id_lista', $listas)
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
