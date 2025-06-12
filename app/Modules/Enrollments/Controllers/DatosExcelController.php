<?php

namespace App\Modules\Enrollments\Controllers;

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
use App\Services\Registers\ListaInscripcionService;

class DatosExcelController
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
            'inscripciones_guardadas' => [], 'inscripciones_errores' => [],
        ];

        foreach ($datos as $index => $row) {
            if (empty(array_filter($row, fn($value) => trim($value) !== ''))) continue;
            
            $row['fila'] = $index;
            $tutorsData[$row[11]] = TutorResolver::extractTutorData($row, $index);
            $olimpistasData[$row[2]] = OlimpistaResolver::extractOlimpistaData($row, $index, $resultadoFinal);
            $profesorData[$row[19]] = ProfesorResolver::extractProfesorData($row, $index, $resultadoFinal);
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
                throw new \Exception("EL CI del responsable no se encuentra registrado");
            }

            // Registrar inscripciones con la lista asociada
            InscripcionesProcessor::save($sanitizedData, $ci_responsable, $resultadoFinal);

            if (
                !empty($resultadoFinal['tutores_errores']) ||
                !empty($resultadoFinal['olimpistas_errores']) ||
                !empty($resultadoFinal['inscripciones_errores']) ||
                !empty($resultadoFinal['profesores_errores'])
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
}
