<?php

namespace App\Services\Excel;

use App\Modules\Persons\Controllers\OlympistController;
use App\Modules\Persons\Requests\StoreOlympistRequest;
use Illuminate\Support\Facades\Validator;
use App\Modules\Persons\Models\Person;
use App\Modules\Olympiads\Models\Department;
use App\Modules\Olympiads\Models\Province;
use App\Modules\Olympiads\Models\School;
use App\Modules\Olympiads\Models\Grade;
use Illuminate\Http\Request;

class OlympistProcessor
{
    public static function save(array $olympistsData, array &$finalResponse)
    {
        $controller = app(OlympistController::class);

        foreach ($olympistsData as $olympist) {
            try {
                // // Validación simple manual por si falta CI
                // if (empty($olympist['olympist_ci'])) {
                //     throw new \Exception("El campo 'cedula_identidad' no puede ser null");
                // }
                
                //Validación tipo de dato cédula de identidad
                if (is_numeric($olympist['olympist_ci'])) {
                    
                    //validación de cédula de identidad única
                    if(Person::where('person_ci', $olympist['olympist_ci'])->exists()){
                        $finalResponse['olympists_saved'][] = [
                            'ci' => $olympist['olympist_ci'],
                            'message' => 'La cédula de identidad "'.$olympist['olympist_ci'].'" ya está registrada',
                            'row' => $olympist['index'] + 2
                        ];
                        continue;
                    }
                    

                    //Validación departamento.
                    if(!Department::where('department_name', $olympist['department'])->exists()){
                        self::addErrorOlympist(
                            $finalResponse,
                            $olympist['olympist_ci'],
                            'El departamento "'.$olympist['department'].'" no es válido',
                            $olympist['index'] + 2
                        );
                    }
                    // Validación Provincia
                    if(!Province::where('province_name', $olympist['province'])->exists()){
                        self::addErrorOlympist(
                            $finalResponse,
                            $olympist['olympist_ci'],
                            'La provincia "'.$olympist['province'].'" no es válida',
                            $olympist['index'] + 2
                        );
                    }
                    // Validación para unidad educativa válida
                    if(!School::where('school_name', $olympist['school'])->exists()){
                        self::addErrorOlympist(
                            $finalResponse,
                            $olympist['olympist_ci'],
                            'La unidad educativa "'.$olympist['school'].'" no es válida',
                            $olympist['index'] + 2
                        );
                        $olympist['school'] = 1;
                    }else{
                        $school = School::where('school_name', $olympist['school'])->first();
                        $olympist['school'] = $school->school_id;
                    }
                    
                    //Validación para grado válido
                    if(!Grade::where('grade_name', $olympist['grade_id'])-> exists()){
                        self::addErrorOlympist(
                            $finalResponse,
                            $olympist['olympist_ci'],
                            'El grado "'.$olympist['grade_id'].'" no es válido, formato esperado: "1ro Secundaria", "3ro Primaria", etc.',
                            $olympist['index'] + 2
                        );
                        $olympist['grade_id'] = null; // Asignar null si no es válido
                    } else {
                        $grade = Grade::where('grade_name', $olympist['grade_id'])->first();
                        $olympist['grade_id'] = $grade->grade_id;
                    }
                    
                    // Usar reglas y mensajes personalizados del FormRequest
                    $formRequest = new StoreOlympistRequest();
                    $validator = Validator::make(
                        $olympist,
                        $formRequest->rules(),
                        $formRequest->messages()
                    );
                    if ($validator->fails()) {
                        foreach ($validator->errors()->all() as $message) {
                            self::addErrorOlympist(
                                $finalResponse,
                                $olympist['olympist_ci'] ?? 'desconocido',
                                $message,
                                $olympist['index'] + 2
                            );
                        }
                    }
                    
                    $lastError = end($finalResponse['olympists_errors']);
                    if ($lastError && $lastError['ci'] == $olympist['olympist_ci']) {
                        continue;
                    }else{
                        // Si la validación pasa, proceder a llamar al controlador
                        $request = new Request($olympist);
                        $response = $controller->store($request);
                    }

                    if ($response->getStatusCode() === 201) {
                        $finalResponse['olympists_saved'][] = $olympist;
                    } else {
                        $finalResponse['olympists_errors'][] = [
                            'ci' => $olympist['olympist_ci'],
                            'message' => $response->getContent(),
                            'row' => $olympist['index'] + 2
                        ];
                    }
                }else{
                    self::addErrorOlympist(
                        $finalResponse,
                        $olympist['olympist_ci'] ?? 'desconocido',
                        'La cédula de identidad del olimpista debe ser un número entero',
                        $olympist['index'] + 2
                    );
                    continue;
                }
                
                
            } catch (\Throwable $e) {
                $finalResponse['olympists_errors'][] = [
                    'ci' => $olympist['olympist_ci'] ?? 'desconocido',
                    'message' => json_encode(['error' => $e->getMessage()]),
                    'row' => $olympist['index'] + 2
                ];
            }
        }
    }
    private static function addErrorOlympist(array &$finalResponse, $ci, $message, $row)
    {
        // Buscar si ya hay un error con ese CI y fila
        $index = null;
        foreach ($finalResponse['olympists_errors'] as $i => $error) {
            if ($error['ci'] == $ci && $error['row'] == $row) {
                $index = $i;
                break;
            }
        }

        if ($index !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($finalResponse['olympists_errors'][$index]['message'])) {
                $finalResponse['olympists_errors'][$index]['message'] = [];
                if (isset($finalResponse['olympists_errors'][$index]['message'])) {
                    // Migrar error plano si existe
                    $finalResponse['olympists_errors'][$index]['message'][] = $finalResponse['olympists_errors'][$index]['message'];
                    unset($finalResponse['olympists_errors'][$index]['message']);
                }
                if (isset($finalResponse['olympists_errors'][$index]['message'])) {
                    $finalResponse['olympists_errors'][$index]['errores'][] = $finalResponse['olympists_errors'][$index]['message'];
                    unset($finalResponse['olympists_errors'][$index]['message']);
                }
            }

            $finalResponse['olympists_errors'][$index]['message'][] = $message;
        } else {
            // No existe, crear nuevo
            $finalResponse['olympists_errors'][] = [
                'ci' => $ci,
                'message' => [$message],
                'row' => $row
            ];
        }
    }
}
