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
            'area_1' => $row[14],  // Columna 15 (Área 1)
            'nivel_categoria' => $row[15],  // Columna 17 (Nivel/Categoría)
        ];
    }
}
