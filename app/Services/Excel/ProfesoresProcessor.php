<?php

namespace App\Services\Excel;

// use App\Http\Controllers\ProfesorController;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Services\Registers\PersonaService;
use Illuminate\Support\Facades\Validator;

class ProfesoresProcessor
{
    public static function save(array $profesoresData, array &$resultado): void
    {
        // $controller = app(ProfesorController::class);

        foreach ($profesoresData as $profesor) {
            try{
                if($profesor['ci'] == null || $profesor['nombres'] == null || $profesor['apellidos'] == null || $profesor['celular'] == null 
                || $profesor['correo_electronico'] == null ){
                    $resultado['profesores_errores'][] = [
                        'fila' => $profesor['fila']+2,
                        'error' => 'Ingrese todos los campos del profesor',
                    ];
                    continue;
                }
                if (Persona::where('ci_persona', $profesor['ci'])->exists()) {
                    $resultado['profesores_omitidos'][] = [
                        'ci' => $profesor['ci'],
                        'message' => 'Ya existe en la base de datos'
                    ];
                    continue;
                }
                
                $filteredProfesor = [
                    'nombres' => $profesor['nombres'],
                    'apellidos' => $profesor['apellidos'],
                    'ci' => $profesor['ci'],
                    'celular' => $profesor['celular'],
                    'correo_electronico' => $profesor['correo_electronico'],
                    'rol_parentesco' => $profesor['rol_parentesco'],
                ];

                $formRequest = new StorePersonaRequest();
                $validator = Validator::make(
                    $filteredProfesor,
                    $formRequest->rules(),
                    $formRequest->messages()
                );
                
                if ($validator->fails()) {
                    $resultado['profesores_errores'][] = [
                        // 'ci' => $profesor['ci'],
                        'error' => $validator->errors()->first(),
                        'fila' => $profesor['fila'] + 2
                    ];
                    continue;
                }
                // Registro si la validación fue exitosa
                $validated = $validator->validated();
                $persona = PersonaService::register($validated);
                $resultado['profesores_guardados'][] = $filteredProfesor;

            }catch (\Throwable $e) {
                $resultado['profesores_errores'][] = [
                    'fila' => $profesor['fila'] + 2,
                    'error' => $e->getMessage()
                ];
            }
        }
    }
}
