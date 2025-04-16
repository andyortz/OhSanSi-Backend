<?php

namespace App\Services\ImportHelpers;

class AreaResolver
{
    /**
     * Extraer los datos del área desde la fila del Excel.
     * 
     * @param array $row
     * @return array
     */
    public static function extractAreaData(array $row): array
    {
        return [
            'area_1' => $row[15],  // Columna 16 (Área 1)
            'area_2' => $row[16],  // Columna 17 (Área 2, puede ser null)
            'nivel_categoria' => $row[17],  // Columna 18 (Nivel/Categoría)
        ];
    }
}
