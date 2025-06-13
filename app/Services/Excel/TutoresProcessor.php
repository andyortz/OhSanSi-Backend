<?php

namespace App\Services\Excel;

use App\Modules\Persons\Models\Persona;
use App\Modules\Persons\Requests\StoreTutorRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\Registers\PersonaService;

class TutoresProcessor
{
    public static function save(array $tutorsData, array &$resultado): void
    {
        foreach ($tutorsData as $tutor) {
            try {
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
                

                
                // Validamos si existe errores, sino continuamos a ver si el tutor ya existe
                if ($validator->fails()) {
                    $resultado['tutores_errores'][] = [
                        'ci' => $tutor['ci'] ?? 'Desconocido',
                        'message' => $validator->errors()->all(),
                        'fila' => $tutor['fila'] + 2
                    ];
                    continue;
                }else if (Persona::where('ci_persona', $tutor['ci'])->exists()) {
                    $resultado['tutores_omitidos'][] = [
                        'ci' => $tutor['ci'],
                        'message' => 'El tutor ya se encuentra registrado en el sistema',
                        'fila'=>$tutor['fila']+2
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
                    'message' => $e->getMessage(),
                    'fila' => $tutor['fila'] + 2
                ];
            }
        }
    }
}
