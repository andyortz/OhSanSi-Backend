<?php

namespace App\Services\Excel;

use App\Http\Controllers\OlimpystController;
use App\Http\Requests\StoreOlympiadParticipantRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Persona;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Colegio;
use App\Models\Grado;
use App\CustomModels\Person;
use App\CustomModels\Departament;
use App\CustomModels\Province;
use App\CustomModels\School;
use App\CustomModels\Grade;
use Illuminate\Http\Request;

class OlimpystsProcessor
{
    public static function save(array $olimpystsData, array &$answerFinal)
    {
        $controller = app(OlimpistaController::class);

        foreach ($olimpystsData as $olimpyst) {
            try {
                
                //Validación tipo de dato cédula de identidad
                if (is_numeric($olimpyst['ci'])) {
                    
                    //validación de cédula de identidad única
                    if(Person::where('ci_person', $olimpyst['ci'])->exists()){
                        $answerFinal['olimpysts_saved'][] = [
                            'ci' => $olimpyst['ci'],
                            'message' => 'La cédula de identidad "'.$olimpyst['ci'].'" ya está registrada',
                            'row' => $olimpyst['row'] + 2
                        ];
                        continue;
                    }
                    

                    //Validación departamento.
                    if(!Departament::where('departament_name', $olimpyst['departament'])->exists()){
                        self::agregarErrorOlimpista(
                            $answerFinal,
                            $olimpyst['ci'],
                            'El departamento "'.$olimpyst['departament'].'" no es válido',
                            $olimpyst['row'] + 2
                        );
                    }
                    // Validación Provincia
                    if(!Province::where('province_name', $olimpyst['province'])->exists()){
                        self::agregarErrorOlimpista(
                            $answerFinal,
                            $olimpyst['ci'],
                            'La provincia "'.$olimpyst['provincia'].'" no es válida',
                            $olimpyst['fila'] + 2
                        );
                    }
                    // Validación para unidad educativa válida
                    if(!Colegio::where('nombre_colegio', $olimpyst['unidad_educativa'])->exists()){
                        self::agregarErrorOlimpista(
                            $answerFinal,
                            $olimpyst['ci'],
                            'La unidad educativa "'.$olimpyst['unidad_educativa'].'" no es válida',
                            $olimpyst['fila'] + 2
                        );
                        $olimpyst['unidad_educativa'] = 1;
                    }else{
                        $colegio = Colegio::where('nombre_colegio', $olimpyst['unidad_educativa'])->first();
                        $olimpyst['unidad_educativa'] = $colegio->id_colegio;
                    }
                    
                    //Validación para grado válido
                    if(!Grado::where('nombre_grado', $olimpyst['id_grado'])-> exists()){
                        self::agregarErrorOlimpista(
                            $answerFinal,
                            $olimpyst['ci'],
                            'El grado "'.$olimpyst['id_grado'].'" no es válido, formato esperado: "1ro Secundaria", "3ro Primaria", etc.',
                            $olimpyst['fila'] + 2
                        );
                        $olimpyst['id_grado'] = null; // Asignar null si no es válido
                    } else {
                        $grado = Grado::where('nombre_grado', $olimpyst['id_grado'])->first();
                        $olimpyst['id_grado'] = $grado->id_grado;
                    }
                    
                    // Usar reglas y mensajes personalizados del FormRequest
                    $formRequest = new StoreOlimpistaRequest();
                    $validator = Validator::make(
                        $olimpyst,
                        $formRequest->rules(),
                        $formRequest->messages()
                    );
                    if ($validator->fails()) {
                        foreach ($validator->errors()->all() as $mensaje) {
                            self::agregarErrorOlimpista(
                                $answerFinal,
                                $olimpyst['ci'] ?? 'desconocido',
                                $mensaje,
                                $olimpyst['fila'] + 2
                            );
                        }
                    }
                    
                    $ultimoError = end($answerFinal['olimpistas_errores']);
                    if ($ultimoError && $ultimoError['ci'] == $olimpyst['ci']) {
                        continue;
                    }else{
                        // Si la validación pasa, proceder a llamar al controlador
                        $request = new Request($olimpyst);
                        $response = $controller->store($request);
                    }

                    if ($response->getStatusCode() === 201) {
                        $answerFinal['olimpysts_saved'][] = $olimpyst;
                    } else {
                        $answerFinal['olimpistas_errores'][] = [
                            'ci' => $olimpyst['ci'],
                            'message' => $response->getContent(),
                            'fila' => $olimpyst['fila'] + 2
                        ];
                    }
                }else{
                    self::agregarErrorOlimpista(
                        $answerFinal,
                        $olimpyst['ci'] ?? 'desconocido',
                        'La cédula de identidad del olimpista debe ser un número entero',
                        $olimpyst['fila'] + 2
                    );
                    continue;
                }
                
                
            } catch (\Throwable $e) {
                $answerFinal['olimpistas_errores'][] = [
                    'ci' => $olimpyst['ci'] ?? 'desconocido',
                    'message' => json_encode(['error' => $e->getMessage()]),
                    'fila' => $olimpyst['fila'] + 2
                ];
            }
        }
    }
    private static function agregarErrorOlimpista(array &$answerFinal, $ci, $mensaje, $fila)
    {
        // Buscar si ya hay un error con ese CI y fila
        $indice = null;
        foreach ($answerFinal['olimpistas_errores'] as $i => $error) {
            if ($error['ci'] == $ci && $error['fila'] == $fila) {
                $indice = $i;
                break;
            }
        }

        if ($indice !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($answerFinal['olimpistas_errores'][$indice]['message'])) {
                $answerFinal['olimpistas_errores'][$indice]['message'] = [];
                if (isset($answerFinal['olimpistas_errores'][$indice]['message'])) {
                    // Migrar error plano si existe
                    $answerFinal['olimpistas_errores'][$indice]['message'][] = $answerFinal['olimpistas_errores'][$indice]['message'];
                    unset($answerFinal['olimpistas_errores'][$indice]['message']);
                }
                if (isset($answerFinal['olimpistas_errores'][$indice]['message'])) {
                    $answerFinal['olimpistas_errores'][$indice]['errores'][] = $answerFinal['olimpistas_errores'][$indice]['message'];
                    unset($answerFinal['olimpistas_errores'][$indice]['message']);
                }
            }

            $answerFinal['olimpistas_errores'][$indice]['message'][] = $mensaje;
        } else {
            // No existe, crear nuevo
            $answerFinal['olimpistas_errores'][] = [
                'ci' => $ci,
                'message' => [$mensaje],
                'fila' => $fila
            ];
        }
    }
}
