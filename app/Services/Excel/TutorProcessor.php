<?php

namespace App\Services\Excel;

use App\Modules\Persons\Models\Person;
use App\Modules\Persons\Requests\StoreTutorRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\Registers\PersonService;

class TutorProcessor
{
    public static function save(array $tutorsData, array &$finalResponse): void
    {
        foreach ($tutorsData as $tutor) {
            try {
                $filteredTutor = [
                    'names' => $tutor['nombres'],
                    'surnames' => $tutor['apellidos'],
                    'ci' => $tutor['ci'],
                    'phone' => $tutor['celular'],
                    'email' => $tutor['correo_electronico'],
                    // 'rol_parentesco' => $tutor['rol_parentesco'],
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
                    $finalResponse['tutors_errors'][] = [
                        'ci' => $tutor['ci'] ?? 'Desconocido',
                        'message' => $validator->errors()->all(),
                        'row' => $tutor['index'] + 2
                    ];
                    continue;
                }else if (Person::where('person_ci', $tutor['ci'])->exists()) {
                    $finalResponse['tutors_omitted'][] = [
                        'ci' => $tutor['ci'],
                        'message' => 'El tutor ya se encuentra registrado en el sistema',
                        'row'=>$tutor['index']+2
                    ];
                    continue;
                }

                // Registro si la validación fue exitosa
                $validated = $validator->validated();
                $person = PersonService::register($validated);

                $finalResponse['tutors_saved'][] = $filteredTutor;
            } catch (\Throwable $e) {
                $finalResponse['tutors_errors'][] = [
                    'ci' => $tutor['ci'],
                    'message' => $e->getMessage(),
                    'row' => $tutor['index'] + 2
                ];
            }
        }
    }
}
