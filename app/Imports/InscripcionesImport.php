<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Services\ImportHelpers\AreaResolver;
use App\Services\ImportValidators\InscripcionValidator;
use App\Services\OlimpiadaService;
use App\Services\ExcelProcessing\ExcelService;

class InscripcionesImport implements ToCollection
{
    public array $processedData = [];
    public array $rawRows = [];

    public function collection(Collection $rows)
    {
        // Skip headers and example row
        $rows->shift(); // Headers
        $rows->shift(); // Example row

        // Get active olympiad
        $olympiad = OlimpiadaService::getOlimpiadaAbierta();
        if (!$olympiad) {
            logger()->error("No active olympiad found. Import aborted.");
            return;
        }

        $olympiadId = $olympiad->id_olimpiada;
        $maxAreas = $olympiad->max_categorias_olimpista;

        // Get valid areas for this olympiad
        $validAreas = AreaResolver::getValidAreas($olympiadId);

        foreach ($rows as $index => $row) {
            $rowArray = array_slice($row->toArray(), 0, 18); // Solo columnas A-R
            $this->rawRows[] = $rowArray;
        
            // Detener procesamiento si toda la fila está vacía
            if (empty(array_filter($rowArray))) {
                logger()->info("Row $index is empty. Stopping import.");
                break; // Aquí detenemos si encontramos una fila completamente vacía
            }
        
            $data = InscripcionValidator::validarFila(
                $rowArray,
                $index,
                $validAreas,
                $olympiadId,
                $maxAreas
            );
        
            if (!$data) continue;
        
            $tutorId = ExcelService::registerTutor($data['tutor']);
        
            if (!$tutorId) {
                logger()->error("Row $index: Tutor could not be registered.");
                continue;
            }
        
            $data['tutor']['id_tutor'] = $tutorId;
            $this->processedData[] = $data;
        
            logger()->info("Row $index processed successfully.", $data);
        }
    }
}
