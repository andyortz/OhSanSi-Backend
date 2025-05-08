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
        $columnMap = [
            0 => 'Nombre estudiante',
            1 => 'Apellido estudiante',
            2 => 'CI estudiante',
            3 => 'RU',
            4 => 'Correo estudiante',
            5 => 'Departamento',
            6 => 'Provincia',
            7 => 'Unidad Educativa',
            8 => 'Grado',
            9 => 'Nombre tutor',
            10 => 'Apellido tutor',
            11 => 'Celular tutor',
            12 => 'CI tutor',
            13 => 'Correo tutor',
            14 => 'Área',
            15 => 'Nivel',
            16 => 'Nombre profesor',
            17 => 'Apellido profesor',
            18 => 'Celular profesor',
            19 => 'CI profesor',
            20 => 'Correo profesor',
        ];
        
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

            if(!is_string($row[0]) && $row[0] != null) return $this->errorFila('Nombre(s)',$row[0],$index, 0);
            if(!is_string($row[1]) && $row[1] != null) return $this->errorFila('Apellidos(s)',$row[1],$index, 1);
            if(!is_numeric($row[2]) && $row[2] != null) return $this->errorFila('CI Olimpista', $row[2], $index, 2);
            if(!is_numeric($row[3]) && $row[3] != null) return $this->errorFila('Fecha de Nacimiento', $row[3], $index, 3);
            if(!is_string($row[4])) return $this->errorFila('Correo electrónico', $row[4], $index, 4);

            $departamento = Departamento::where('nombre_departamento', $row[5])->first();
            if (!$departamento) return $this->errorFila('Departamento', $row[5], $index, 5);
            $row[5] = $departamento->id_departamento;

            $provincia = ProvinciaResolver::resolve($row[6], $row[5]);
            if (!$provincia) return $this->errorFila('Provincia', $row[6], $index, 6);
            $row[6] = $provincia;

            $colegio = ColegioResolver::resolve($row[5], $row[6]);
            if (!$colegio) return $this->errorFila('Unidad educativa', $row[7], $index, 7);
            $row[7] = $colegio;

            $grado = GradoResolver::resolve($row[8]);
            if (!$grado) return $this->errorFila('Grado', $row[8], $index, 8);
            $row[8] = $grado;
            //validaciones tutor legal<.
            if(!is_string($row[9]) && $row[9] != null) return $this->errorFila('Nombre(s) tutor legal',$row[9],$index, 9);
            if(!is_string($row[10]) && $row[10] != null) return $this->errorFila('Apellidos(s) tutor legal',$row[10],$index, 10);
            if(!is_numeric($row[11]) && $row[11] != null) return $this->errorFila('CI tutor legal', $row[11], $index, 11);
            if(!is_numeric($row[12]) & $row[12]!= null) return $this->errorFila('Celular', $row[12], $index, 12);
            if(!is_string($row[13]) & $row[13]!= null) return $this->errorFila('Correo electrónico tutor legal', $row[13], $index, 13);
            $nivel = NivelResolver::resolve($row[15]);
            if (!$nivel) return $this->errorFila('Nivel', $row[15], $index, 15);
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

    private function errorFila($campo, $valor, $fila, $columnaIndex = null)
    {
        $columnaLetra = $columnaIndex !== null ? $this->columnaLetra($columnaIndex) : 'N/A';

        return response()->json([
            'error' => "Error en la celda $columnaLetra" . ($fila + 2) . " - $campo inválido.",
            'fila' => $fila + 2, // +2 porque la fila 0 es el encabezado
            'columna' => $columnaIndex,
            'columna_letra' => $columnaLetra,
            'valor' => $valor
        ], 422);
    }
    private function columnaLetra($index)
    {
        $letra = '';
        while ($index >= 0) {
            $letra = chr($index % 26 + 65) . $letra;
            $index = intdiv($index, 26) - 1;
        }
        return $letra;
    }

}
