<?php

namespace App\Services\Excel;

use App\Http\Controllers\OlimpistaController;
use App\Http\Requests\StoreOlimpistaRequest;

class OlimpistasProcessor
{
    public static function save(array $olimpistasData, array &$resultado)
    {
        $controller = app(OlimpistaController::class);

        foreach ($olimpistasData as $olimpista) {
            try {
                // Validación simple manual por si falta CI
                if (empty($olimpista['cedula_identidad'])) {
                    throw new \Exception("El campo 'cedula_identidad' no puede ser null");
                }

                // Crear request y simular el request
                $request = StoreOlimpistaRequest::create('/fake-url', 'POST', $olimpista);
                $response = $controller->store($request);

                if ($response->getStatusCode() === 201) {
                    $resultado['olimpistas_guardados'][] = $olimpista;
                } else {
                    $resultado['olimpistas_errores'][] = [
                        'ci' => $olimpista['cedula_identidad'],
                        'error' => $response->getContent()
                    ];
                }
            } catch (\Throwable $e) {
                $resultado['olimpistas_errores'][] = [
                    'ci' => $olimpista['cedula_identidad'] ?? 'desconocido',
                    'error' => json_encode(['error' => $e->getMessage()])
                ];
            }
        }
    }
}
