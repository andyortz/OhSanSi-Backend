<?php

namespace App\Services\ImportHelpers;

class ProfesorResolver
{
    /**
     * Extraer los datos del profesor desde la fila del Excel.
     * 
     * @param array $row
     * @return array
     */

    
    public static function extractProfesorData(array $row, $fila): array
    {
        return [
            'nombres' => self::normaliceText($row[16]),  
            'apellidos' => self::normaliceText($row[17]),  
            'ci' => $row[18],  
            'celular' => strval($row[19]),
            'correo_electronico' => $row[20], 
            'rol_parentesco' => 'Madre',
            'fila' => $fila,  // Asumiendo que la fila es la primera columna
        ];
    }

    private static function normaliceText($text) {
        $replacements = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'ñ' => 'n', 'Ñ' => 'N'
        ];
        $sin_tildes = strtr($text, $replacements);
        // Convertir a mayúsculas
        return strtoupper($sin_tildes);
    }
}
