<?php

namespace App\Modules\Enrollments\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Persons\Models\Person;
// use App\Services\ImportHelpers\ProvinciaResolver; 
// use App\Services\ImportHelpers\ColegioResolver;
// use App\Services\ImportHelpers\GradoResolver;
// use App\Services\ImportHelpers\NivelResolver;
use App\Services\ImportHelpers\TutorResolver;
use App\Services\ImportHelpers\OlympistResolver;
// use App\Services\ImportHelpers\AreaResolver;
use App\Services\ImportHelpers\TeacherResolver;
use App\Services\Excel\TutorProcessor;
use App\Services\Excel\OlympistProcessor;
use App\Services\Excel\TeacherProcessor;
use App\Services\Excel\EnrollmentProcessor;

class DatosExcelController
{
    public function cleanDates(Request $request)
    {
        $datos = $request->input('data');
        $ci_responsible = $request->input('enrollment_responsible_ci');
        // $columnMap = [
        //     0 => 'Nombre estudiante',
        //     1 => 'Apellido estudiante',
        //     2 => 'CI estudiante',
        //     3 => 'RU',
        //     4 => 'Correo estudiante',
        //     5 => 'Departamento',
        //     6 => 'Provincia',
        //     7 => 'Unidad Educativa',
        //     8 => 'Grado',
        //     9 => 'Nombre tutor',
        //     10 => 'Apellido tutor',
        //     11 => 'Celular tutor',
        //     12 => 'CI tutor',
        //     13 => 'Correo tutor',
        //     14 => 'Área',
        //     15 => 'Nivel',
        //     16 => 'Nombre profesor',
        //     17 => 'Apellido profesor',
        //     18 => 'Celular profesor',
        //     19 => 'CI profesor',
        //     20 => 'Correo profesor',
        // ];
        
        if (!is_array($datos)) {
            return response()->json(['error' => 'El archivo no contiene datos válidos.'], 400);
        }

        if (!$ci_responsible || !is_numeric($ci_responsible)) {
            return response()->json(['error' => 'CI del responsable inválido.'], 422);
        }
        // Validar ahora que el responsable ya esté registrado
        if (!Person::where('person_ci', $ci_responsible)->exists()) {
            throw new \Exception("EL CI del responsable no se encuentra registrado");
        }
        $sanitizedData = [];
        $tutorsData = [];
        $olimpistasData = [];
        $profesorData = [];
        $areasData = [];

        $finalResponse = [
            'tutors_saved' => [], 'tutors_omitted' => [], 'tutors_errors' => [],
            'olympists_saved' => [], 'olympists_errors' => [],
            'teachers_saved' => [], 'teachers_errors' => [],
            'enrollments_saved' => [], 'enrollments_errors' => [],
        ];

        foreach ($datos as $index => $row)
        {
            if (empty(array_filter($row, fn($value) => trim($value) !== ''))) continue;
            
            $row['index'] = $index;
            $tutorsData[$row[11]] = TutorResolver::extractTutorData($row);
            $olimpistasData[$row[2]] = OlympistResolver::extractOlympistData($row, $finalResponse);
            $profesorData[$row[19]] = TeacherResolver::extractProfesorData($row,);
            // $areasData[] = AreaResolver::extractAreaData($row);

            
            $sanitizedData[] = $row;
        }
        
        try {
            DB::beginTransaction();

            // Guardar primero tutores, profesores y olimpistas
            TutorProcessor::save($tutorsData, $finalResponse);
            TeacherProcessor::save($profesorData, $finalResponse);
            OlympistProcessor::save($olimpistasData, $finalResponse);

            

            // Registrar inscripciones con la lista asociada
            EnrollmentProcessor::save($sanitizedData, $ci_responsible, $finalResponse);

            if (
                !empty($finalResponse['tutors_errors']) ||
                !empty($finalResponse['olympists_errors']) ||
                !empty($finalResponse['enrollments_errors']) ||
                !empty($finalResponse['teachers_errors'])
            ) {
                throw new \Exception("Se encontraron errores en los datos. No se guardó nada.");
            }

            DB::commit();

            return response()->json([
                'message' => 'Datos validados y guardados correctamente.',
                'response' => $finalResponse
            ], 200);
            
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Se produjo un error y no se guardó ningún dato.',
                'error' => $e->getMessage(),
                'response' => $finalResponse
            ], 500);
        }
    }
}
