<?php

namespace App\Services\Excel;

use App\Http\Controllers\OlimpistaController;
use App\Http\Requests\StoreOlimpistaRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Persona;
use Illuminate\Http\Request;

class OlimpistasProcessor
{
    public static function save(array $olimpistasData, array &$resultado)
    {
        $controller = app(OlimpistaController::class);

        foreach ($olimpistasData as $olimpista) {
            try {
                // // Validación simple manual por si falta CI
                // if (empty($olimpista['cedula_identidad'])) {
                //     throw new \Exception("El campo 'cedula_identidad' no puede ser null");
                // }
                if (!is_numeric($olimpista['cedula_identidad'])) {
                    $resultado['olimpistas_errores'][] = [
                        'ci' => $olimpista['cedula_identidad'],
                        'message' => 'La cédula de identidad del olimpista debe ser un número entero',
                        'fila'=>$olimpista['fila']+2
                    ];
                    continue;
                }
                if(Persona::where('ci_persona', $olimpista['cedula_identidad'])->exists()){
                    $resultado['olimpistas_guardados'][] = [
                            'ci' => $olimpista['cedula_identidad'],
                            'message' => 'El olimpista ya se encuentra registrado en el sistema',
                            'fila'=>$olimpista['fila']+2
                        ];
                    continue;
                }
                // Usar reglas y mensajes personalizados del FormRequest
                $formRequest = new StoreOlimpistaRequest();
                $validator = Validator::make(
                    $olimpista,
                    $formRequest->rules(),
                    $formRequest->messages()
                );

                if ($validator->fails()) {
                    $resultado['olimpistas_errores'][] = [
                        'ci' => $olimpista['cedula_identidad'] ?? 'desconocido',
                        'error' => $validator->errors()->all(),
                        'fila' => $olimpista['fila'] + 2
                    ];
                    continue;
                }

                // Si la validación pasa, proceder a llamar al controlador
                $request = new Request($olimpista);
                $response = $controller->store($request);

                if ($response->getStatusCode() === 201) {
                    $resultado['olimpistas_guardados'][] = $olimpista;
                } else {
                    $resultado['olimpistas_errores'][] = [
                        'ci' => $olimpista['cedula_identidad'],
                        'error' => $response->getContent(),
                        'fila' => $olimpista['fila'] + 2
                    ];
                }
            } catch (\Throwable $e) {
                $resultado['olimpistas_errores'][] = [
                    'ci' => $olimpista['cedula_identidad'] ?? 'desconocido',
                    'error' => json_encode(['error' => $e->getMessage()]),
                    'fila' => $olimpista['fila'] + 2
                ];
            }
        }
    }
}
