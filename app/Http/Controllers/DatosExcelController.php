<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Persona;
use App\Services\ImportHelpers\ProvinciaResolver;
use App\Services\ImportHelpers\ColegioResolver;
use App\Services\ImportHelpers\GradoResolver;
use App\Services\ImportHelpers\NivelResolver;
use App\Services\ImportHelpers\TutorResolver;
use App\Services\ImportHelpers\OlimpistaResolver;
use App\Services\ImportHelpers\AreaResolver;
use App\Services\ImportHelpers\ProfesorResolver;
use App\Services\Excel\TutoresProcessor;
use App\Services\Excel\OlimpistasProcessor;
use App\Services\Excel\ProfesoresProcessor;
use App\Services\Excel\InscripcionesProcessor;

class DatosExcelController extends Controller
{
    public function cleanDates(Request $request)
    {
        $datos = $request->input('data');
        $ci_responsable = $request->input('ci_responsable_inscripcion');

        if (!is_array($datos)) {
            return response()->json(['error' => 'El archivo no contiene datos válidos.'], 400);
        }

        if (!$ci_responsable || !is_numeric($ci_responsable)) {
            return response()->json(['error' => 'CI del responsable inválido.'], 422);
        }

        $sanitizedData = [];
        $tutorsData = [];
        $olimpistasData = [];
        $profesorData = [];
        $areasData = [];

        $resultadoFinal = [
            'tutores_guardados' => [], 'tutores_omitidos' => [], 'tutores_errores' => [],
            'olimpistas_guardados' => [], 'olimpistas_errores' => [],
            'profesores_guardados' => [], 'profesores_errores' => [],
            'inscripciones_guardadas' => [], 'inscripciones_errores' => []
        ];

        foreach ($datos as $index => $row) {
            if (empty(array_filter($row))) continue;

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

            $tutorsData[$row[11]] = TutorResolver::extractTutorData($row);
            $olimpistasData[$row[2]] = OlimpistaResolver::extractOlimpistaData($row);
            $profesorData[$row[19]] = ProfesorResolver::extractProfesorData($row);
            $areasData[] = AreaResolver::extractAreaData($row);

            $sanitizedData[] = $row;
        }

        try {
            DB::beginTransaction();

            // Guardar primero tutores, profesores y olimpistas
            TutoresProcessor::save($tutorsData, $resultadoFinal);
            ProfesoresProcessor::save($profesorData, $resultadoFinal);
            OlimpistasProcessor::save($olimpistasData, $resultadoFinal);

            // Validar ahora que el responsable ya esté registrado
            if (!Persona::where('ci_persona', $ci_responsable)->exists()) {
                throw new \Exception("El CI del responsable no existe en la base de datos.");
            }

            // Registrar inscripciones con la lista asociada
            InscripcionesProcessor::save($sanitizedData, $ci_responsable, $resultadoFinal);

            if (
                !empty($resultadoFinal['tutores_errores']) ||
                !empty($resultadoFinal['olimpistas_errores']) ||
                !empty($resultadoFinal['inscripciones_errores'])
            ) {
                throw new \Exception("Se encontraron errores en los datos. No se guardó nada.");
            }

            DB::commit();

            return response()->json([
                'message' => 'Datos validados y guardados correctamente.',
                'resultado' => $resultadoFinal
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Se produjo un error y no se guardó ningún dato.',
                'error' => $e->getMessage(),
                'resultado' => $resultadoFinal
            ], 500);
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
