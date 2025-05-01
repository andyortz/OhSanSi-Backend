<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Provincia;
use App\Http\Requests\StoreOlimpistaRequest;
use App\Services\ImportHelpers\GradoResolver;
use App\Services\ImportHelpers\ProvinciaResolver;
use App\Services\ImportHelpers\ColegioResolver;
use App\Services\ImportHelpers\TutorResolver;
use App\Services\ImportHelpers\OlimpistaResolver;
use App\Services\ImportHelpers\AreaResolver;
use App\Services\ImportHelpers\NivelResolver;
use Illuminate\Http\Request;

use App\Http\Controllers\TutoresControllator;
use App\Http\Controllers\OlimpistaController;
use App\Services\ImportHelpers\ProfesorResolver;


class DatosExcelController extends Controller
{
    public function cleanDates(Request $request)
    {
        $datos = $request->input('data');

        if (!is_array($datos)) {
            return response()->json(['error' => 'El archivo no contiene datos válidos.'], 400);
        }

        $sanitizedData = [];
        $tutorsData = [];
        $olimpistasData = [];
        $areasData = [];
        $profesorData = [];

        // JSON acumulador de resultados
        $resultadoFinal = [
            'tutores_guardados' => [],
            'tutores_omitidos' => [],
            'tutores_errores' => [],
            'olimpistas_guardados' => [],
            'olimpistas_errores' => []
        ];

        foreach ($datos as $index => $row) {
            if (empty(array_filter($row))) break;

            // Validaciones y resoluciones
            $departamento = Departamento::where('nombre_departamento', $row[5])->first();
            if (!$departamento) return $this->errorFila('Departamento', $row[5], $index);
            $row[5] = $departamento->id_departamento;

            $provincia = ProvinciaResolver::resolve($row[6], $row[5]);
            if (!$provincia) return $this->errorFila('Provincia', $row[6], $index);
            $row[6] = $provincia;

            $colegio = ColegioResolver::resolve($row[5], $row[6]);
            if (!$colegio) return $this->errorFila('Unidad educativa', $row[7], $index);
            $row[7] = $colegio;

            $grado = GradoResolver::resolve($row[8]);
            if (!$grado) return $this->errorFila('Grado', $row[8], $index);
            $row[8] = $grado;

            $nivel = NivelResolver::resolve($row[15]);
            if (!$nivel) return $this->errorFila('Nivel', $row[15], $index);
            $row[15] = $nivel;

            $tutor = TutorResolver::extractTutorData($row);
            $tutorsData[$tutor['ci']] = $tutor;

            $olimpista = OlimpistaResolver::extractOlimpistaData($row);
            $olimpistasData[$olimpista['cedula_identidad']] = $olimpista;

            $areasData[] = AreaResolver::extractAreaData($row);
            $profesorData[] = ProfesorResolver::extractProfesorData($row);
            $sanitizedData[] = $row;
        }

        $this->saveTutores(array_values($tutorsData), $resultadoFinal);
        $this->saveOlimpistas(array_values($olimpistasData), $resultadoFinal);

        return response()->json([
            'message' => 'Datos validados y convertidos correctamente.',
            'tutors_data' => array_values($tutorsData),
            'olimpistas_data' => array_values($olimpistasData),
            'areas_data' => $areasData,
            'profesor_data' => $profesorData,
            'sanitized_data' => $sanitizedData,
            'resultado' => $resultadoFinal
        ], 200);
    }

    private function saveTutores(array $tutorsData, array &$resultado)
    {
        $controller = app(TutoresControllator::class);

        foreach ($tutorsData as $tutor) {
            if (\App\Models\Persona::where('ci_persona', $tutor['ci'])->exists()) {
                $resultado['tutores_omitidos'][] = [
                    'ci' => $tutor['ci'],
                    'message' => 'Ya existe en la base de datos'
                ];
                continue;
            }

            $filteredTutor = [
                'nombres' => $tutor['nombres'],
                'apellidos' => $tutor['apellidos'],
                'ci' => $tutor['ci'],
                'celular' => $tutor['celular'],
                'correo_electronico' => $tutor['correo_electronico'],
                'rol_parentesco' => $tutor['rol_parentesco'],
            ];

            try {
                $request = new \Illuminate\Http\Request($filteredTutor);
                $response = $controller->store($request);

                if ($response->getStatusCode() === 201) {
                    $resultado['tutores_guardados'][] = $filteredTutor;
                } else {
                    $resultado['tutores_errores'][] = [
                        'ci' => $tutor['ci'],
                        'error' => $response->getContent()
                    ];
                }
            } catch (\Throwable $e) {
                $resultado['tutores_errores'][] = [
                    'ci' => $tutor['ci'],
                    'error' => $e->getMessage()
                ];
            }
        }
    }

    private function saveOlimpistas(array $olimpistasData, array &$resultado)
    {
        $controller = app(OlimpistaController::class);

        foreach ($olimpistasData as $olimpista) {
            $request = new StoreOlimpistaRequest();
            $request->merge($olimpista);

            try {
                $response = $controller->store($request);
                if ($response->getStatusCode() === 201) {
                    $resultado['olimpistas_guardados'][] = $olimpista;
                } else {
                    $resultado['olimpistas_errores'][] = [
                        'ci' => $olimpista['cedula_identidad'],
                        'error' => $response->getContent()
                    ];
                }
            } catch (\Throwable $e) {
                $resultado['olimpistas_errores'][] = [
                    'ci' => $olimpista['cedula_identidad'],
                    'error' => $e->getMessage()
                ];
            }
        }
    }
    private function saveInscripcion(array $sanitizedData, array &$resultado)
    {
        $controller = app(InscripcionNivelesController::class); 

        foreach ($sanitizedData as $data) {
            $request = new StoreOlimpistaRequest();
            $request->merge($data);

            try {
                $response = $controller->store($request);
                if ($response->getStatusCode() === 201) {
                    $resultado['inscripciones_guardadas'][] = $data;
                } else {
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['cedula_identidad'],
                        'error' => $response->getContent()
                    ];
                }
            } catch (\Throwable $e) {
                $resultado['inscripciones_errores'][] = [
                    'ci' => $data['cedula_identidad'],
                    'error' => $e->getMessage()
                ];
            }
        }
    }
    
    private function errorFila($campo, $valor, $fila)
    {
        return response()->json([
            'error' => "$campo inválido en la fila " . ($fila + 1),
            'value' => $valor
        ], 422);
    }
}
