<?php

namespace App\Services\ImportHelpers;

class TeacherResolver
{
    /**
     * Extraer los datos del profesor desde la fila del Excel.
     * 
     * @param array $row
     * @return array
     */
    public static function extractTeacherData(array $row): array
    {
        return [
            'names' => $row[16],  
            'surnames' => $row[17],  
            'ci' => $row[18],  
            'phone' => strval($row[19]),
            'email' => $row[20], 
            // 'rol_parentesco' => 'Madre',
            'row' => $row[21],  // Asumiendo que la fila es la primera columna
        ];
    }
}
