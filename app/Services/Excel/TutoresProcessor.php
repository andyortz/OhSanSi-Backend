<?php

namespace App\Services\Excel;

use App\Models\Persona;
use App\Http\Controllers\TutoresControllator;
use App\Http\Requests\StorePersonaRequest;
use Illuminate\Http\Request;

class TutoresProcessor
{
    public static function save(array $tutorsData, array &$resultado): void
    {
        $controller = app(TutoresControllator::class);

        foreach ($tutorsData as $tutor) {
            if (Persona::where('ci_persona', $tutor['ci'])->exists()) {
                $resultado['tutores_omitidos'][] = [
                    'ci' => $tutor['ci'],
                    'message' => 'Ya existe en la base de datos'
                ];
                continue;
            }

            $filteredTutor = [
                'nombres' => $tutor['nombres'],
                'apellidos' => $tutor['apellidos'],
                'ci' => $tutor['ci'],
                'celular' => $tutor['celular'],
                'correo_electronico' => $tutor['correo_electronico'],
                'rol_parentesco' => $tutor['rol_parentesco'],
            ];

            try {
                $request = new StorePersonaRequest();
                $request->merge($filteredTutor);

                $response = $controller->store($request);

                if ($response->getStatusCode() === 201) {
                    $resultado['tutores_guardados'][] = $filteredTutor;
                } else {
                    $resultado['tutores_errores'][] = [
                        'ci' => $tutor['ci'],
                        'error' => $response->getContent()
                    ];
                }
            } catch (\Throwable $e) {
                $resultado['tutores_errores'][] = [
                    'ci' => $tutor['ci'],
                    'error' => $e->getMessage()
                ];
            }
        }
    }
}
