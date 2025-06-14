<?php

namespace App\Services\Excel;

// use App\Modules\Persons\Controllers\ProfesorController

use App\Modules\Persons\Models\Person;
use App\Modules\Persons\Requests\StoreTeacherRequest;
use App\Services\Registers\PersonService;
use Illuminate\Support\Facades\Validator;

class TeacherProcessor
{
    public static function save(array $teachersData, array &$finalResponse): void
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
                        $finalResponse,
                        $teacher['ci'] ?? 'Desconocido',
                        'Debe completar todos los campos del profesor si va a llenar alguno.',
                        $teacher['index'] + 2
                    );
                    continue;
                }else if(count($full) == 5){
                    $filteredTeacher = [
                        'names' => $teacher['names'],
                        'surnames' => $teacher['surnames'],
                        'ci' => $teacher['ci'],
                        'phone' => $teacher['phone'],
                        'email' => $teacher['email'],
                        // 'rol_parentesco' => $teacher['rol_parentesco'],
                    ];
                    $formRequest = new StoreTeacherRequest();
                    $validator = Validator::make(
                        $filteredTeacher,
                        $formRequest->rules(),
                        $formRequest->messages()
                    );
                    
                    if ($validator->fails()) {
                        foreach($validator -> errors()->all() as $message){
                            self::addErrorTeacher(
                                $finalResponse,
                                $teacher['ci'] ?? 'Desconocido',
                                $message,
                                $teacher['index'] + 2
                            );
                        }
                        continue;
                    }else if(Person::where('person_ci', $teacher['ci'])->exists()) {
                        $finalResponse['teachers_omitted'][] = [
                            'ci' => $teacher['ci'],
                            'message' => 'El profesor ya se encuentra registrado en el sistema',
                            'row'=>$teacher['index']+2
                        ];
                        continue;
                    }
                    // Registro si la validación fue exitosa
                    $validated = $validator->validated();
                    $persona = PersonService::register($validated);
                    $finalResponse['teachers_saved'][] = $filteredTeacher;
                }
            }catch (\Throwable $e) {
                $finalResponse['teachers_errors'][] = [
                    'ci' => $teacher['ci'] ?? 'Desconocido',
                    'message' => $e->getMessage(),
                    'row' => $teacher['index'] + 2
                ];
            }
        }
    }
    private static function addErrorTeacher(array &$finalResponse, $ci, $message, $row)
    {
        // Buscar si ya hay un error con ese CI y fila
        $index = null;
        foreach ($finalResponse['teachers_errors'] as $i => $error) {
            if ($error['ci'] == $ci && $error['row'] == $row) {
                $index = $i;
                break;
            }
        }

        if ($index !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($finalResponse['teachers_errors'][$index]['message'])) {
                $finalResponse['teachers_errors'][$index]['message'] = [];
                if (isset($finalResponse['teachers_errors'][$index]['message'])) {
                    // Migrar error plano si existe
                    $finalResponse['teachers_errors'][$index]['message'][] = $finalResponse['teachers_errors'][$index]['message'];
                    unset($finalResponse['teachers_errors'][$index]['message']);
                }
                if (isset($finalResponse['teachers_errors'][$index]['message'])) {
                    $finalResponse['teachers_errors'][$index]['errores'][] = $finalResponse['teachers_errors'][$index]['message'];
                    unset($finalResponse['teachers_errors'][$index]['message']);
                }
            }

            $finalResponse['teachers_errors'][$index]['message'][] = $message;
        } else {
            // No existe, crear nuevo
            $finalResponse['teachers_errors'][] = [
                'ci' => $ci,
                'message' => [$message],
                'row' => $row
            ];
        }
    }
}
