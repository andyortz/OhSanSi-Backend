<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Persona;
use App\CustomModels\Person;
use App\Services\ImportHelpers\TutorResolver;
use App\Services\ImportHelpers\OlimpystResolver;
use App\Services\ImportHelpers\AreaResolver;
use App\Services\ImportHelpers\TeacherResolver;
use App\Services\Excel\TutorsProcessor;
use App\Services\Excel\OlimpystssProcessor;
use App\Services\Excel\TeachersProcessor;
use App\Services\Excel\InscripcionesProcessor;
use App\Services\Registers\ListaInscripcionService;

class DatosExcelController extends Controller
{
    public function cleanDates(Request $request)
    {
        $data = $request->input('data');
        $ci_responsible = $request->input('ci_responsable_inscripcion');
        
        if (!is_array($data)) {
            return response()->json(['error' => 'El archivo no contiene datos válidos.'], 400);
        }

        if (!$ci_responsible || !is_numeric($ci_responsible)) {
            return response()->json(['error' => 'CI del responsable inválido.'], 422);
        }

        $sanitizedData = [];
        $tutorsData = [];
        $olimpystsData = [];
        $teachersData = [];
        // $areasData = [];

        $answerFinal = [
            'tutors_saved' => [], 'tutors_omitted' => [], 'tutors_errors' => [],
            'olimpysts_saved' => [], 'olimpysts_errors' => [],
            'teachers_saved' => [], 'teachers_errors' => [],
            'registrations_saved' => [], 'registrations_errors' => [],
        ];

        foreach ($data as $index => $row) {
            if (empty(array_filter($row, fn($value) => trim($value) !== ''))) continue;
        
            $row['row'] = $index;
            $tutorsData[$row[11]] = TutorResolver::extractTutorData($row);
            $olimpystsData[$row[2]] = OlimpystResolver::extractOlimpystData($row, $answerFinal);
            $teachersData[$row[19]] = TeacherResolver::extractTeacherData($row);
            // $areasData[] = AreaResolver::extractAreaData($row);

            $sanitizedData[] = $row;
        }
        
        try {
            DB::beginTransaction();

            // Guardar primero tutores, profesores y olimpistas
            TutorsProcessor::save($tutorsData, $answerFinal);
            TeachersProcessor::save($teachersData, $answerFinal);
            OlimpystsProcessor::save($olimpystsData, $answerFinal);

            // Validar ahora que el responsable ya esté registrado
            if (!Persona::where('ci_persona', $ci_responsible)->exists()) {
                throw new \Exception("EL CI del responsable no se encuentra registrado");
            }

            // Registrar inscripciones con la lista asociada
            InscripcionesProcessor::save($sanitizedData, $ci_responsible, $answerFinal);

            if (
                !empty($answerFinal['tutors_errors']) ||
                !empty($answerFinal['olimpysts_errors']) ||
                !empty($answerFinal['registrations_errors']) ||
                !empty($answerFinal['teachers_errors']) 
            ) {
                throw new \Exception("Se encontraron errores en los datos. No se guardó nada.");
            }

            DB::commit();

            return response()->json([
                'message' => 'Datos validados y guardados correctamente.',
                'answer' => $answerFinal
            ], 200);
            
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Se produjo un error y no se guardó ningún dato.',
                'error' => $e->getMessage(),
                'answer' => $answerFinal
            ], 500);
        }
    }
}
