<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Importable;

class InscripcionesImport
{
    use Importable;

    public array $rawRows = [];

    public function import($file): void
    {
        $dataImport = new DataImport();
        Excel::import($dataImport, $file);

        $rows = $dataImport->rows;

        // Eliminar la primera fila si ya fue usada como encabezado
        // (en WithHeadingRow ya no se necesita hacer shift)

        foreach ($rows as $index => $row) {
            $rowArray = array_values(array_slice($row->toArray(), 0, 21));

            if (empty(array_filter($rowArray))) {
                logger()->info("Row $index is empty. Stopping the import.");
                break;
            }

            $this->rawRows[] = $rowArray;
        }
    }
}
