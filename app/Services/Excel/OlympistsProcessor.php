<?php

namespace App\Services\Excel;

use App\Modules\Olympist\Controllers\OlympistController;
// use App\Http\Requests\StoreOlympiadParticipantRequest;
use App\Modules\Olympist\Requests\StoreOlympiadParticipantRequest;
use Illuminate\Support\Facades\Validator;
use App\Modules\Olympist\Models\Person;
use App\Modules\Olympist\Models\Departament;
use App\Modules\Olympist\Models\Province;
use App\Modules\Olympist\Models\School;
use App\Modules\Olympiad\Models\Grade;
use Illuminate\Http\Request;

class OlympistsProcessor
{
    public static function save(array $olympistsData, array &$answerFinal)
    {
        $controller = app(OlympistController::class);

        foreach ($olympistsData as $olimpyst) {
            try {
                
                //Validación tipo de dato cédula de identidad
                if (is_numeric($olimpyst['ci'])) {
                    
                    //validación de cédula de identidad única
                    if(Person::where('ci_person', $olimpyst['ci'])->exists()){
                        $answerFinal['olympists_saved'][] = [
                            'ci' => $olimpyst['ci'],
                            'message' => 'La cédula de identidad "'.$olimpyst['ci'].'" ya está registrada',
                            'row' => $olimpyst['row'] + 2
                        ];
                        continue;
                    }
                    

                    //Verify if the department exists in the database
                    if(!Departament::where('departament_name', [strtoupper($olimpyst['departament'])])->exists()){
                        self::addOlympistError(
                            $answerFinal,
                            $olimpyst['ci'],
                            'El departamento "'.$olimpyst['departament'].'" no es válido',
                            $olimpyst['row'] + 2
                        );
                    }
                    //Verify if the province exists in the database
                    if(!Province::where('province_name', $olimpyst['province'])->exists()){
                        self::addOlympistError(
                            $answerFinal,
                            $olimpyst['ci'],
                            'La provincia "'.$olimpyst['provincie'].'" no es válida',
                            $olimpyst['row'] + 2
                        );
                    }
                    //Verify if the school exists in the database
                    if(!School::where('school_name', $olimpyst['school'])->exists()){
                        self::addOlympistError(
                            $answerFinal,
                            $olimpyst['ci'],
                            'La unidad educativa "'.$olimpyst['unidad_educativa'].'" no es válida',
                            $olimpyst['row'] + 2
                        );
                        $olimpyst['school'] = 1;
                    }else{
                        $school = School::where('school_name', $olimpyst['school'])->first();
                        $olimpyst['school'] = $school->id_school;
                    } 
                    //Verify if the grade exists in the database
                    if(!Grade::where('grade_name', $olimpyst['id_grade'])-> exists()){
                        self::addOlympistError(
                            $answerFinal,
                            $olimpyst['ci'],
                            'El grado "'.$olimpyst['id_grade'].'" no es válido, formato esperado: "1ro Secundaria", "3ro Primaria", etc.',
                            $olimpyst['row'] + 2
                        );
                        $olimpyst['id_grade'] = null; // Asignar null si no es válido
                    } else {
                        $grade = Grade::where('grade_name', $olimpyst['id_grade'])->first();
                        $olimpyst['id_grade'] = $grade->id_grade;
                    }
                    
                    // Usar reglas y mensajes personalizados del FormRequest
                    $formRequest = new StoreOlympiadParticipantRequest();
                    $validator = Validator::make(
                        $olimpyst,
                        $formRequest->rules(),
                        $formRequest->messages()
                    );
                    if ($validator->fails()) {
                        foreach ($validator->errors()->all() as $message) {
                            self::addOlympistError(
                                $answerFinal,
                                $olimpyst['ci'] ?? 'desconocido',
                                $message,
                                $olimpyst['row'] + 2
                            );
                        }
                    }
                    
                    $lastError = end($answerFinal['olympists_errors']);
                    if ($lastError && $lastError['ci'] == $olimpyst['ci']) {
                        continue;
                    }else{
                        // Si la validación pasa, proceder a llamar al controlador
                        $request = new Request($olimpyst);
                        $response = $controller->store($request);
                    }

                    if ($response->getStatusCode() === 201) {
                        $answerFinal['olympists_saved'][] = $olimpyst;
                    } else {
                        $answerFinal['olympists_errors'][] = [
                            'ci' => $olimpyst['ci'],
                            'message' => $response->getContent(),
                            'row' => $olimpyst['row'] + 2
                        ];
                    }
                }else{
                    self::addOlympistError(
                        $answerFinal,
                        $olimpyst['ci'] ?? 'desconocido',
                        'La cédula de identidad del olimpista debe ser un número entero',
                        $olimpyst['row'] + 2
                    );
                    continue;
                }
                
                
            } catch (\Throwable $e) {
                $answerFinal['olympists_errors'][] = [
                    'ci' => $olimpyst['ci'] ?? 'desconocido',
                    'message' => json_encode(['error' => $e->getMessage()]),
                    'row' => $olimpyst['row'] + 2
                ];
            }
        }
    }
    private static function addOlympistError(array &$answerFinal, $ci, $message, $row)
    {
        // Buscar si ya hay un error con ese CI y fila
        $index = null;
        foreach ($answerFinal['olympists_errors'] as $i => $error) {
            if ($error['ci'] == $ci && $error['row'] == $row) {
                $index = $i;
                break;
            }
        }

        if ($index !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($answerFinal['olympists_errors'][$index]['message'])) {
                $answerFinal['olympists_errors'][$index]['message'] = [];
                if (isset($answerFinal['olympists_errors'][$index]['message'])) {
                    // Migrar error plano si existe
                    $answerFinal['olympists_errors'][$index]['message'][] = $answerFinal['olympists_errors'][$index]['message'];
                    unset($answerFinal['olympists_errors'][$index]['message']);
                }
                if (isset($answerFinal['olympists_errors'][$index]['message'])) {
                    $answerFinal['olympists_errors'][$index]['errores'][] = $answerFinal['olympists_errors'][$index]['message'];
                    unset($answerFinal['olympists_errors'][$index]['message']);
                }
            }

            $answerFinal['olympists_errors'][$index]['message'][] = $message;
        } else {
            // No existe, crear nuevo
            $answerFinal['olympists_errors'][] = [
                'ci' => $ci,
                'message' => [$message],
                'row' => $row
            ];
        }
    }
}
