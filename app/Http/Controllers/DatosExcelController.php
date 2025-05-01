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
use Illuminate\Http\Request;

use App\Http\Controllers\TutoresControllator;
use App\Http\Controllers\OlimpistaController;
use App\Services\ImportHelpers\ProfesorResolver;


class DatosExcelController extends Controller
{
    public function cleanDates(Request $request)
    {
        $datos = $request->input('raw_data');

        $sanitizedData = [];
        $tutorsData = [];
        $olimpistasData = [];
        $areasData = [];
        $profesorData = [];

        foreach ($datos as $index => $row) {
            $rowArray = array_slice($row, 0, 20);
            if (empty(array_filter($rowArray))) break;

            $row[8] = GradoResolver::resolve($row[8]) ?? null;

            $departamento = Departamento::where('nombre_departamento', $row[5])->first();
            $row[5] = $departamento?->id_departamento;

            $row[6] = ProvinciaResolver::resolve($row[6], $row[5]) ?? null;
            $row[7] = ColegioResolver::resolve($row[5], $row[6]);

            $tutor = TutorResolver::extractTutorData($row);
            $tutorsData[] = $tutor;

            $olimpista = OlimpistaResolver::extractOlimpistaData($row);
            $olimpistasData[] = $olimpista;

            $areasData[] = AreaResolver::extractAreaData($row);

            $profesorData[] = ProfesorResolver::extractProfesorData($row);

            $sanitizedData[] = $row;
        }

        $this->saveTutores($tutorsData);
        //$this->saveOlimpistas($olimpistasData);


        return response()->json([
            'message' => 'Data sanitized successfully.',
            'sanitized_data' => $sanitizedData,
            'tutors_data' => $tutorsData,
            'olimpistas_data' => $olimpistasData,
            'areas_data' => $areasData,
            'profesor_data' => $profesorData,
        ], 200);
    }

    private function saveTutores(array $tutorsData)
{
    $controller = app(TutoresControllator::class);

    foreach ($tutorsData as $tutor) {
        // Filtramos solo los campos que el controlador espera
        $filteredTutor = [
            'nombres' => $tutor['nombres'],
            'apellidos' => $tutor['apellidos'],
            'ci' => $tutor['ci'],
            'celular' => $tutor['celular'],
            'correo_electronico' => $tutor['correo_electronico']
        ];

        // Creamos el request simulado
        $request = new \Illuminate\Http\Request($filteredTutor);

        // Ejecutamos el controlador
        $response = $controller->store($request);

        // Verificamos el resultado
        $status = $response->getStatusCode();

        if ($status === 201) {
            logger()->info("Tutor guardado correctamente", ['ci' => $tutor['ci']]);
        } else {
            logger()->error("Error al guardar tutor", [
                'ci' => $tutor['ci'],
                'response' => $response->getContent()
            ]);
        }
    }
}



    private function saveOlimpistas(array $olimpistasData)
    {
        $controller = app(OlimpistaController::class);

        foreach ($olimpistasData as $olimpista) {
            // Creamos un nuevo StoreOlimpistaRequest manualmente
            $request = new StoreOlimpistaRequest();
            $request->merge($olimpista);  // Pasamos los datos al request

            // Ahora llamamos al método store() usando el StoreOlimpistaRequest
            $response = $controller->store($request);

            if ($response->getStatusCode() === 201) {
                logger()->info("Olimpista guardado", ['ci' => $olimpista['cedula_identidad']]);
            } else {
                logger()->error("Error al guardar olimpista", ['ci' => $olimpista['cedula_identidad'], 'error' => $response->getContent()]);
            }
        }
    }
}
