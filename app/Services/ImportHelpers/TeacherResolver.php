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

    
    public static function extractProfesorData(array $row): array
    {
        return [
            'names' => self::normalizeText($row[16]),  
            'surnames' => self::normalizeText($row[17]),  
            'ci' => $row[18],  
            'phone' => strval($row[19]),
            'email' => $row[20], 
            // 'rol_parentesco' => 'Madre',
            'index' => $row['index'],  // Asumiendo que la fila es la primera columna
        ];
    }

    private static function normalizeText($text) {
        $replacements = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'ñ' => 'n', 'Ñ' => 'N'
        ];
        $without_accents = strtr($text, $replacements);
        // Convertir a mayúsculas
        return strtoupper($without_accents);
    }
}
