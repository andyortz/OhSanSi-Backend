<?php

namespace App\Services\Excel;


use App\Modules\Olympist\Models\Person;
use App\Http\Requests\StoreTutorRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\Registers\PersonService;

class TutorsProcessor
{
    public static function save(array $tutorsData, array &$answerFinal): void
    {
        foreach ($tutorsData as $tutor) {
            try {
                if (Person::where('ci_person', $tutor['ci'])->exists()) {
                    $answerFinal['tutors_omitted'][] = [
                        'ci' => $tutor['ci'],
                        'message' => 'El tutor ya se encuentra registrado en el sistema',
                        'row'=>$tutor['row']+2
                    ];
                    continue;
                }

                $filteredTutor = [
                    'names' => $tutor['names'],
                    'surnames' => $tutor['surnames'],
                    'ci' => $tutor['ci'],
                    'phone' => $tutor['phone'],
                    'email' => $tutor['email'],
                    // 'rol_parentesco' => $tutor['rol_parentesco'],
                ];

                // Validación manual utilizando las reglas y mensajes del FormRequest
                $formRequest = new StoreTutorRequest();
                $validator = Validator::make(
                    $filteredTutor,
                    $formRequest->rules(),
                    $formRequest->messages()
                );

                if ($validator->fails()) {
                    $answerFinal['tutors_errors'][] = [
                        'ci' => $tutor['ci'] ?? 'Desconocido',
                        'message' => $validator->errors()->all(),
                        'row' => $tutor['row'] + 2
                    ];
                    continue;
                }

                // Registro si la validación fue exitosa
                $validated = $validator->validated();
                $person = PersonService::register($validated);

                $answerFinal['tutors_saved'][] = $filteredTutor;
            } catch (\Throwable $e) {
                $answerFinal['tutors_errors'][] = [
                    'ci' => $tutor['ci'],
                    'message' => $e->getMessage(),
                    'row' => $tutor['row'] + 2
                ];
            }
        }
    }
}
