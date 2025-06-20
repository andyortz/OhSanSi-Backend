<?php

namespace App\Modules\Persons\Services\ImportHelpers;

class TutorResolver
{
    /**
     * Extraer los datos del tutor desde la fila del Excel.
     * 
     * @param array $row
     * @return array
     */
    

    public static function extractTutorData(array $row): array
    {
        return [
            'names' => self::normalizeText($row[9]),  // Columna 10 (Nombre del tutor)
            'surnames' => self::normalizeText($row[10]),  // Columna 11 (Apellido del tutor)
            'ci' => $row[11],  // Columna 12 (CI del tutor)
            'phone' => strval($row[12]), // Columna 13 (Celular del tutor)
            'email' => $row[13],  // Columna 14 (Correo electrónico del tutor)
            // 'rol_parentesco' => 'Madre',
            'index' => $row['index'],
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
