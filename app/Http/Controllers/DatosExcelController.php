<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Services\ImportHelpers\ProvinciaResolver;
use App\Services\ImportHelpers\ColegioResolver;
use App\Services\ImportHelpers\GradoResolver;
use App\Services\ImportHelpers\NivelResolver;
use App\Services\ImportHelpers\TutorResolver;
use App\Services\ImportHelpers\OlimpistaResolver;
use App\Services\ImportHelpers\AreaResolver;
use App\Services\ImportHelpers\ProfesorResolver;
use App\Services\ImportHelpers\InscripcionResolver;
use App\Services\Excel\TutoresProcessor;
use App\Services\Excel\OlimpistasProcessor;
use App\Services\Excel\ProfesoresProcessor;
use App\Services\Excel\InscripcionesProcessor;

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
        $profesorData = [];
        $areasData = [];
        $inscripcionesData = [];

        $resultadoFinal = [
            'tutores_guardados' => [], 'tutores_omitidos' => [], 'tutores_errores' => [],
            'olimpistas_guardados' => [], 'olimpistas_errores' => [],
            'profesores_guardados' => [], 'profesores_errores' => [],
            'inscripciones_guardadas' => [], 'inscripciones_errores' => []
        ];

        foreach ($datos as $index => $row) {
            if (empty(array_filter($row))) continue;

            // Validaciones
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

            // Extracción de datos
            $tutorsData[$row[11]] = TutorResolver::extractTutorData($row);
            $olimpistasData[$row[2]] = OlimpistaResolver::extractOlimpistaData($row);
            $profesorData[$row[19]] = ProfesorResolver::extractProfesorData($row);
            $areasData[] = AreaResolver::extractAreaData($row);
            $inscripcionesData[] = InscripcionResolver::extract($row);
            $sanitizedData[] = $row;
        }

        TutoresProcessor::save($tutorsData, $resultadoFinal);
            OlimpistasProcessor::save($olimpistasData, $resultadoFinal);
            ProfesoresProcessor::save($profesorData, $resultadoFinal);
            InscripcionesProcessor::save($sanitizedData, $resultadoFinal);

        try {
            DB::beginTransaction();

            

            DB::commit();

            return response()->json([
                'message' => 'Datos validados y convertidos correctamente.',
                'tutors_data' => array_values($tutorsData),
                'olimpistas_data' => array_values($olimpistasData),
                'areas_data' => $areasData,
                'profesor_data' => array_values($profesorData),
                'sanitized_data' => $sanitizedData,
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
