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
            $request = new StoreOlimpistaRequest();
            $request->merge($olimpista);

            try {
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
                    'ci' => $olimpista['cedula_identidad'],
                    'error' => $e->getMessage()
                ];
            }
        }
    }
}
