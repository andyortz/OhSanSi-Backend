<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Importable;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EnrollmentsImport
{
    use Importable;

    public array $rawRows = [];

    public function import($file): void
    {
        $dataImport = new DataImport();
        Excel::import($dataImport, $file);

        $rows = $dataImport->rows;

        foreach ($rows as $index => $row) {
            $rowArray = array_values(array_slice($row->toArray(), 0, 21));

            // if (empty(array_filter($rowArray))) {
            //     logger()->info("Row $index is empty. Stopping the import.");
            //     continue;
            // }

            // Convertir fecha si viene como número serial
            if (isset($rowArray[3]) && is_numeric($rowArray[3])) {
                $rowArray[3] = Date::excelToDateTimeObject($rowArray[3])->format('Y-m-d');
            }

            $this->rawRows[] = $rowArray;
        }
    }
}
