<?php

namespace App\Services\Excel;

use App\Http\Controllers\TutoresControllator;
use Illuminate\Http\Request;
use App\Models\Persona;

class ProfesoresProcessor
{
    public static function save(array $profesoresData, array &$resultado): void
    {
        $controller = app(TutoresControllator::class);

        foreach ($profesoresData as $profesor) {
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

            try {
                $request = new Request($filteredProfesor);
                $response = $controller->store($request);

                if ($response->getStatusCode() === 201) {
                    $resultado['profesores_guardados'][] = $filteredProfesor;
                } else {
                    $resultado['profesores_errores'][] = [
                        'ci' => $profesor['ci'],
                        'error' => $response->getContent()
                    ];
                }
            } catch (\Throwable $e) {
                $resultado['profesores_errores'][] = [
                    'ci' => $profesor['ci'],
                    'error' => $e->getMessage()
                ];
            }
        }
    }
}
