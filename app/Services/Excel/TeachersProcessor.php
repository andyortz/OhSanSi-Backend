<?php

namespace App\Services\Excel;

// use App\Http\Controllers\ProfesorController;


use App\Modules\Olympist\Models\Person;
use App\Modules\Olympist\Requests\StorePersonRequest;
use App\Services\Registers\PersonService;
use Illuminate\Support\Facades\Validator;

class TeachersProcessor
{
    public static function save(array $teachersData, array &$answerFinal): void
    {
        // $controller = app(ProfesorController::class);

        foreach ($teachersData as $teacher) {
            try{
                $fields = [
                    $teacher['ci'],
                    $teacher['names'],
                    $teacher['surnames'],
                    $teacher['phone'],
                    $teacher['email'],
                ];

                // Cuenta cuántos campos tienen algún valor (ni null ni string vacío)
                $full = array_filter($fields, fn($value) => $value !== null && $value !== '');

                if (count($full) > 0 && count($full) < count($fields)) {
                    self::addErrorTeacher(
                        $answerFinal,
                        $teacher['ci'] ?? 'Desconocido',
                        'Debe completar todos los campos del profesor.',
                        $teacher['row'] + 2
                    );
                    continue;
                }else if(count($full) == 5){
                    if (is_numeric($teacher['ci']) && Person::where('ci_person', $teacher['ci'])->exists()) {
                        $answerFinal['teachers_omitted'][] = [
                            'ci' => $teacher['ci'],
                            'message' => 'El profesor ya se encuentra registrado en el sistema',
                            'row'=>$teacher['row']+2
                        ];
                        continue;
                    }
                    
                    $filteredTeacher = [
                        'names' => $teacher['names'],
                        'surnames' => $teacher['surnames'],
                        'ci_person' => $teacher['ci'],
                        'phone' => $teacher['phone'],
                        'email' => $teacher['email'],
                        // 'rol_parentesco' => $teacher['rol_parentesco'],
                    ];

                    $formRequest = new StorePersonRequest();
                    $validator = Validator::make(
                        $filteredTeacher,
                        $formRequest->rules(),
                        $formRequest->messages()
                    );
                    
                    if ($validator->fails()) {
                        foreach($validator -> errors()->all() as $message){
                            self::addErrorTeacher(
                                $answerFinal,
                                $teacher['ci'] ?? 'Desconocido',
                                $message,
                                $teacher['row'] + 2
                            );
                        }
                        continue;
                    }
                    // Registro si la validación fue exitosa
                    $validated = $validator->validated();
                    $person = PersonService::register($validated);
                    $answerFinal['teachers_saved'][] = $filteredTeacher;
                }
            }catch (\Throwable $e) {
                $answerFinal['teachers_errors'][] = [
                    'teacher' => $teacher['ci'] ?? 'Desconocido',
                    'message' => $e->getMessage(),
                    'row' => $teacher['row'] + 2
                ];
            }
        }
    }
    private static function addErrorTeacher(array &$answerFinal, $ci, $message, $row)
    {
        // Buscar si ya hay un error con ese CI y fila
        $index = null;
        foreach ($answerFinal['teachers_errors'] as $i => $error) {
            if ($error['ci'] == $ci && $error['row'] == $row) {
                $index = $i;
                break;
            }
        }

        if ($index !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($answerFinal['teachers_errors'][$index]['message'])) {
                $answerFinal['teachers_errors'][$index]['message'] = [];
                if (isset($answerFinal['teachers_errors'][$index]['message'])) {
                    // Migrar error plano si existe
                    $answerFinal['teachers_errors'][$index]['message'][] = $answerFinal['teachers_errors'][$index]['message'];
                    unset($answerFinal['teachers_errors'][$index]['message']);
                }
                if (isset($answerFinal['teachers_errors'][$index]['message'])) {
                    $answerFinal['teachers_errors'][$index]['errores'][] = $answerFinal['teachers_errors'][$index]['message'];
                    unset($answerFinal['teachers_errors'][$index]['message']);
                }
            }

            $answerFinal['teachers_errors'][$index]['message'][] = $message;
        } else {
            // No existe, crear nuevo
            $answerFinal['teachers_errors'][] = [
                'ci' => $ci,
                'message' => [$message],
                'row' => $row
            ];
        }
    }
}
