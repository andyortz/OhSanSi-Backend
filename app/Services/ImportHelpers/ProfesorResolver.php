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
    public static function extractProfesorData(array $row): array
    {
        return [
            'nombresProfesor' => $row[16],  
            'apellidosProfesor' => $row[17],  
            'cisProfesor' => $row[18],  
            'celularsProfesor' => $row[19], 
            'correo_electronicosProfesor' => $row[20], 
        ];
    }
}
