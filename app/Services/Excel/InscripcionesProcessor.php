<?php

namespace App\Services\Excel;

use Illuminate\Http\Request;
use App\Http\Controllers\InscripcionNivelesController;

class InscripcionesProcessor
{
    public static function save(array $sanitizedData, array &$resultado): void
    {
        $controller = app(InscripcionNivelesController::class);
        $interesados = self::selectData($sanitizedData);

        foreach ($interesados as $data) {
            try {
                $request = new Request($data);
                $response = $controller->storeOne($request);

                if ($response->getStatusCode() === 201) {
                    $resultado['inscripciones_guardadas'][] = [
                        'ci' => $data['ci'],
                        'nivel' => $data['nivel']
                    ];
                } else {
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['ci'],
                        'error' => $response->getContent()
                    ];
                }
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
        return collect($sanitizedData)->map(function ($item, $index) {
            return [
                'ci' => $item[2],
                'nivel' => $item[15],
                'id_pago' => null,
                'estado' => 'pendiente',
                'ci_tutor_academico' => $item[18] ?? null
            ];
        })->toArray();
    }
}
