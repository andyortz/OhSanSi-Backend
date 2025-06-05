<?php

namespace App\Services\Excel;

use App\Http\Controllers\OlimpistaController;
use App\Http\Requests\StoreOlimpistaRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Persona;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Colegio;
use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\Request;

class OlimpistasProcessor
{
    public static function save(array $olimpistasData, array &$resultado)
    {
        $controller = app(OlimpistaController::class);

        foreach ($olimpistasData as $olimpista) {
            try {
                // // Validación simple manual por si falta CI
                // if (empty($olimpista['cedula_identidad'])) {
                //     throw new \Exception("El campo 'cedula_identidad' no puede ser null");
                // }
                
                //Validación tipo de dato cédula de identidad
                if (is_numeric($olimpista['cedula_identidad'])) {
                    
                    //validación de cédula de identidad única
                    if(Persona::where('ci_persona', $olimpista['cedula_identidad'])->exists()){
                        $resultado['olimpistas_guardados'][] = [
                            'ci' => $olimpista['cedula_identidad'],
                            'message' => 'La cédula de identidad "'.$olimpista['cedula_identidad'].'" ya está registrada',
                            'fila' => $olimpista['fila'] + 2
                        ];
                        continue;
                    }
                    

                    //Validación departamento.
                    if(!Departamento::where('nombre_departamento', $olimpista['departamento'])->exists()){
                        self::agregarErrorOlimpista(
                            $resultado,
                            $olimpista['cedula_identidad'],
                            'El departamento "'.$olimpista['departamento'].'" no es válido',
                            $olimpista['fila'] + 2
                        );
                    }
                    // Validación Provincia
                    if(!Provincia::where('nombre_provincia', $olimpista['provincia'])->exists()){
                        self::agregarErrorOlimpista(
                            $resultado,
                            $olimpista['cedula_identidad'],
                            'La provincia "'.$olimpista['provincia'].'" no es válida',
                            $olimpista['fila'] + 2
                        );
                    }
                    // Validación para unidad educativa válida
                    if(!Colegio::where('nombre_colegio', $olimpista['unidad_educativa'])->exists()){
                        self::agregarErrorOlimpista(
                            $resultado,
                            $olimpista['cedula_identidad'],
                            'La unidad educativa "'.$olimpista['unidad_educativa'].'" no es válida',
                            $olimpista['fila'] + 2
                        );
                        $olimpista['unidad_educativa'] = 1;
                    }else{
                        $colegio = Colegio::where('nombre_colegio', $olimpista['unidad_educativa'])->first();
                        $olimpista['unidad_educativa'] = $colegio->id_colegio;
                    }
                    
                    //Validación para grado válido
                    if(!Grado::where('nombre_grado', $olimpista['id_grado'])-> exists()){
                        self::agregarErrorOlimpista(
                            $resultado,
                            $olimpista['cedula_identidad'],
                            'El grado "'.$olimpista['id_grado'].'" no es válido, formato esperado: "1ro Secundaria", "3ro Primaria", etc.',
                            $olimpista['fila'] + 2
                        );
                        $olimpista['id_grado'] = null; // Asignar null si no es válido
                    } else {
                        $grado = Grado::where('nombre_grado', $olimpista['id_grado'])->first();
                        $olimpista['id_grado'] = $grado->id_grado;
                    }
                    
                    // Usar reglas y mensajes personalizados del FormRequest
                    $formRequest = new StoreOlimpistaRequest();
                    $validator = Validator::make(
                        $olimpista,
                        $formRequest->rules(),
                        $formRequest->messages()
                    );
                    if ($validator->fails()) {
                        foreach ($validator->errors()->all() as $mensaje) {
                            self::agregarErrorOlimpista(
                                $resultado,
                                $olimpista['cedula_identidad'] ?? 'desconocido',
                                $mensaje,
                                $olimpista['fila'] + 2
                            );
                        }
                    }
                    
                    $ultimoError = end($resultado['olimpistas_errores']);
                    if ($ultimoError && $ultimoError['ci'] == $olimpista['cedula_identidad']) {
                        continue;
                    }else{
                        // Si la validación pasa, proceder a llamar al controlador
                        $request = new Request($olimpista);
                        $response = $controller->store($request);
                    }

                    if ($response->getStatusCode() === 201) {
                        $resultado['olimpistas_guardados'][] = $olimpista;
                    } else {
                        $resultado['olimpistas_errores'][] = [
                            'ci' => $olimpista['cedula_identidad'],
                            'message' => $response->getContent(),
                            'fila' => $olimpista['fila'] + 2
                        ];
                    }
                }else{
                    self::agregarErrorOlimpista(
                        $resultado,
                        $olimpista['cedula_identidad'] ?? 'desconocido',
                        'La cédula de identidad del olimpista debe ser un número entero',
                        $olimpista['fila'] + 2
                    );
                    continue;
                }
                
                
            } catch (\Throwable $e) {
                $resultado['olimpistas_errores'][] = [
                    'ci' => $olimpista['cedula_identidad'] ?? 'desconocido',
                    'message' => json_encode(['error' => $e->getMessage()]),
                    'fila' => $olimpista['fila'] + 2
                ];
            }
        }
    }
    private static function agregarErrorOlimpista(array &$resultado, $ci, $mensaje, $fila)
    {
        // Buscar si ya hay un error con ese CI y fila
        $indice = null;
        foreach ($resultado['olimpistas_errores'] as $i => $error) {
            if ($error['ci'] == $ci && $error['fila'] == $fila) {
                $indice = $i;
                break;
            }
        }

        if ($indice !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($resultado['olimpistas_errores'][$indice]['message'])) {
                $resultado['olimpistas_errores'][$indice]['message'] = [];
                if (isset($resultado['olimpistas_errores'][$indice]['message'])) {
                    // Migrar error plano si existe
                    $resultado['olimpistas_errores'][$indice]['message'][] = $resultado['olimpistas_errores'][$indice]['message'];
                    unset($resultado['olimpistas_errores'][$indice]['message']);
                }
                if (isset($resultado['olimpistas_errores'][$indice]['message'])) {
                    $resultado['olimpistas_errores'][$indice]['errores'][] = $resultado['olimpistas_errores'][$indice]['message'];
                    unset($resultado['olimpistas_errores'][$indice]['message']);
                }
            }

            $resultado['olimpistas_errores'][$indice]['message'][] = $mensaje;
        } else {
            // No existe, crear nuevo
            $resultado['olimpistas_errores'][] = [
                'ci' => $ci,
                'message' => [$mensaje],
                'fila' => $fila
            ];
        }
    }
}
