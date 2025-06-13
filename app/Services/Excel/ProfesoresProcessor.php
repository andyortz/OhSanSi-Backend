<?php

namespace App\Services\Excel;

// use App\Modules\Persons\Controllers\ProfesorController

use App\Modules\Persons\Models\Persona;
use App\Modules\Persons\Requests\StoreProfesorRequest;
use App\Services\Registers\PersonaService;
use Illuminate\Support\Facades\Validator;

class ProfesoresProcessor
{
    public static function save(array $profesoresData, array &$resultado): void
    {
        // $controller = app(ProfesorController::class);

        foreach ($profesoresData as $profesor) {
            try{
                $campos = [
                    $profesor['ci'],
                    $profesor['nombres'],
                    $profesor['apellidos'],
                    $profesor['celular'],
                    $profesor['correo_electronico'],
                ];

                // Cuenta cuántos campos tienen algún valor (ni null ni string vacío)
                $llenos = array_filter($campos, fn($valor) => $valor !== null && $valor !== '');

                if (count($llenos) > 0 && count($llenos) < count($campos)) {
                    self::agregarErrorProfesor(
                        $resultado,
                        $profesor['ci'] ?? 'Desconocido',
                        'Debe completar todos los campos del profesor si va a llenar alguno.',
                        $profesor['fila'] + 2
                    );
                    continue;
                }else if(count($llenos) == 5){
                    $filteredProfesor = [
                        'nombres' => $profesor['nombres'],
                        'apellidos' => $profesor['apellidos'],
                        'ci' => $profesor['ci'],
                        'celular' => $profesor['celular'],
                        'correo_electronico' => $profesor['correo_electronico'],
                        'rol_parentesco' => $profesor['rol_parentesco'],
                    ];
                    $formRequest = new StoreProfesorRequest();
                    $validator = Validator::make(
                        $filteredProfesor,
                        $formRequest->rules(),
                        $formRequest->messages()
                    );
                    
                    if ($validator->fails()) {
                        foreach($validator -> errors()->all() as $mensaje){
                            self::agregarErrorProfesor(
                                $resultado,
                                $profesor['ci'] ?? 'Desconocido',
                                $mensaje,
                                $profesor['fila'] + 2
                            );
                        }
                        continue;
                    }else if(Persona::where('ci_persona', $profesor['ci'])->exists()) {
                        $resultado['profesores_omitidos'][] = [
                            'ci' => $profesor['ci'],
                            'message' => 'El profesor ya se encuentra registrado en el sistema',
                            'fila'=>$profesor['fila']+2
                        ];
                        continue;
                    }
                    // Registro si la validación fue exitosa
                    $validated = $validator->validated();
                    $persona = PersonaService::register($validated);
                    $resultado['profesores_guardados'][] = $filteredProfesor;
                }
            }catch (\Throwable $e) {
                $resultado['profesores_errores'][] = [
                    'profesor' => $profesor['ci'] ?? 'Desconocido',
                    'message' => $e->getMessage(),
                    'fila' => $profesor['fila'] + 2
                ];
            }
        }
    }
    private static function agregarErrorProfesor(array &$resultado, $ci, $mensaje, $fila)
    {
        // Buscar si ya hay un error con ese CI y fila
        $indice = null;
        foreach ($resultado['profesores_errores'] as $i => $error) {
            if ($error['ci'] == $ci && $error['fila'] == $fila) {
                $indice = $i;
                break;
            }
        }

        if ($indice !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($resultado['profesores_errores'][$indice]['message'])) {
                $resultado['profesores_errores'][$indice]['message'] = [];
                if (isset($resultado['profesores_errores'][$indice]['message'])) {
                    // Migrar error plano si existe
                    $resultado['profesores_errores'][$indice]['message'][] = $resultado['profesores_errores'][$indice]['message'];
                    unset($resultado['profesores_errores'][$indice]['message']);
                }
                if (isset($resultado['profesores_errores'][$indice]['message'])) {
                    $resultado['profesores_errores'][$indice]['errores'][] = $resultado['profesores_errores'][$indice]['message'];
                    unset($resultado['profesores_errores'][$indice]['message']);
                }
            }

            $resultado['profesores_errores'][$indice]['message'][] = $mensaje;
        } else {
            // No existe, crear nuevo
            $resultado['profesores_errores'][] = [
                'ci' => $ci,
                'message' => [$mensaje],
                'fila' => $fila
            ];
        }
    }
}
