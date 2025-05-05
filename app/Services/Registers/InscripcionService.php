<?php

namespace App\Services\Registers;

use App\Models\DetalleOlimpista;
use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InscripcionesService
{
    public static function register(array $data): Inscripcion
    {
        return DB::transaction(function () use ($data) {
            $detalleOlimpista = DetalleOlimpista::where('ci_olimpista', $data['ci'])->first();

            if (!$detalleOlimpista) {
                throw new \Exception('Olimpista no encontrado en detalle_olimpistas.', 404);
            }

            $estado = strtoupper($data['estado'] ?? 'PENDIENTE');
            $ci_tutor_academico = $data['ci_tutor_academico'] ?? null;

            if (!is_null($ci_tutor_academico) && !\App\Models\Persona::where('ci_persona', $ci_tutor_academico)->exists()) {
                $ci_tutor_academico = null;
            }

            // Si no se manda id_pago, se crea un pago dummy
            $idPago = $data['id_pago'] ?? null;
            if (!$idPago) {
                $pago = Pago::create([
                    'comprobante' => 'PAGO-DUMMY-' . uniqid(),
                    'fecha_pago' => now(),
                    'ci_responsable_inscripcion' => $data['ci'],
                    'monto_pagado' => 0,
                    'verificado' => false,
                    'verificado_en' => now(),
                    'verificado_por' => null
                ]);
                $idPago = $pago->id_pago;
            }

            return Inscripcion::create([
                'id_olimpiada' => $detalleOlimpista->id_olimpiada,
                'id_detalle_olimpista' => $detalleOlimpista->id_detalle_olimpista,
                'ci_tutor_academico' => $ci_tutor_academico,
                'id_pago' => $idPago,
                'id_nivel' => $data['nivel'],
                'estado' => $estado,
                'fecha_inscripcion' => now(),
            ]);
        });
    }
}
