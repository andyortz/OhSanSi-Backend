<?php

namespace App\Services\Excel;

use App\Services\Registers\InscripcionService;

class InscripcionesProcessor
{
    public static function save(array $sanitizedData, int $ci_responsable, array &$resultado): void
    {
        $service = app(InscripcionService::class);
        $interesados = self::selectData($sanitizedData);

        foreach ($interesados as $data) {
            try {
                // Inyectar CI del responsable directamente
                $data['ci_responsable_inscripcion'] = $ci_responsable;

                $inscripcion = $service->register($data);

                $resultado['inscripciones_guardadas'][] = [
                    'ci' => $data['ci'],
                    'nivel' => $data['nivel'],
                    'id_lista' => $inscripcion->id_lista ?? null
                ];
            } catch (\Throwable $e) {
                $resultado['inscripciones_errores'][] = [
                    'ci' => $data['ci'],
                    'error' => $e->getMessage()
                ];
            }
        }
    }

    private static function selectData(array $sanitizedData): array
    {
        return collect($sanitizedData)->map(function ($item) {
            return [
                'ci' => $item[2],
                'nivel' => $item[15],
                'estado' => 'pendiente',
                'ci_tutor_academico' => $item[18] ?? null
            ];
        })->toArray();
    }
}
