<?php

namespace App\Services\Excel;

use App\Models\Persona;
use App\Http\Requests\StoreTutorRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\Registers\PersonaService;

class TutoresProcessor
{
    public static function save(array $tutorsData, array &$resultado): void
    {
        foreach ($tutorsData as $tutor) {
            try {
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

                // Validación manual utilizando las reglas y mensajes del FormRequest
                $formRequest = new StoreTutorRequest();
                $validator = Validator::make(
                    $filteredTutor,
                    $formRequest->rules(),
                    $formRequest->messages()
                );

                if ($validator->fails()) {
                    $resultado['tutores_errores'][] = [
                        'ci' => $tutor['ci'] ?? 'Desconocido',
                        'error' => $validator->errors()->all(),
                        'fila' => $tutor['fila'] + 2
                    ];
                    continue;
                }

                // Registro si la validación fue exitosa
                $validated = $validator->validated();
                $persona = PersonaService::register($validated);

                $resultado['tutores_guardados'][] = $filteredTutor;
            } catch (\Throwable $e) {
                $resultado['tutores_errores'][] = [
                    'ci' => $tutor['ci'],
                    'error' => $e->getMessage(),
                    'fila' => $tutor['fila'] + 2
                ];
            }
        }
    }
}
