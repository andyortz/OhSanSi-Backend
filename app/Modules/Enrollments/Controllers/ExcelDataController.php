<?php

namespace App\Modules\Enrollments\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Persons\Models\Person;
use App\Modules\Persons\Services\ImportHelpers\TutorResolver;
use App\Modules\Persons\Services\ImportHelpers\OlympistResolver;
use App\Modules\Persons\Services\ImportHelpers\TeacherResolver;
use App\Modules\Persons\Services\Processors\TutorProcessor;
use App\Modules\Persons\Services\Processors\OlympistProcessor;
use App\Modules\Persons\Services\Processors\TeacherProcessor;
use App\Modules\Enrollments\Services\Excel\EnrollmentProcessor;

class ExcelDataController
{
    public function cleanDates(Request $request)
    {
        $datos = $request->input('data');
        $ci_responsible = $request->input('enrollment_responsible_ci');
        
        
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
        $dataOlympists = [];
        $dataTeachers = [];
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
            $dataOlympists[$row[2]] = OlympistResolver::extractOlympistData($row, $finalResponse);
            $dataTeachers[$row[19]] = TeacherResolver::extractProfesorData($row,);
            
            $sanitizedData[] = $row;
        }
        
        try {
            DB::beginTransaction();

            // Guardar primero tutores, profesores y olimpistas
            TutorProcessor::save($tutorsData, $finalResponse);
            TeacherProcessor::save($dataTeachers, $finalResponse);
            OlympistProcessor::save($dataOlympists, $finalResponse);

            

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
