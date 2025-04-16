<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InscripcionesImport implements ToCollection
{
    public array $rawRows = [];  // Aquí almacenaremos las filas del Excel

    public function collection(Collection $rows)
    {
        // Eliminar la primera fila que contiene los encabezados
        $rows->shift(); // Headers
        $rows->shift(); // Example row

        // Iterar sobre cada fila del Excel y extraer los datos
        foreach ($rows as $index => $row) {
            $rowArray = array_slice($row->toArray(), 0, 18); // Solo las primeras 18 columnas

            // Detener si la fila está vacía
            if (empty(array_filter($rowArray))) {
                logger()->info("Row $index is empty. Stopping the import.");
                break; // Detener el ciclo cuando encuentre una fila vacía
            }

            // Guardar la fila cruda
            $this->rawRows[] = $rowArray;
        }
    }
}
