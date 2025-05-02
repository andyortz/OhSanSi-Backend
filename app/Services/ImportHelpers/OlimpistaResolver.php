<?php

namespace App\Services\ImportHelpers;

class OlimpistaResolver
{
    /**
     * Extraer los datos del olimpista desde la fila del Excel.
     * 
     * @param array $row
     * @return array
     */
    public static function extractOlimpistaData(array $row): array
    {
        // Convertir la unidad educativa a string
        $unidadEducativa = (string) $row[7];  // Columna 8 (Unidad Educativa)
        
        // Convertir la fecha de nacimiento a formato YYYY-MM-DD
        $fechaNacimiento = self::excelDateToDateString($row[3]);  // Columna 4 (Fecha de nacimiento)

        return [
            'nombres' => $row[0],  // Columna 1 (Nombre del olimpista)
            'apellidos' => $row[1],  // Columna 2 (Apellido del olimpista)
            'cedula_identidad' => $row[2],  // Columna 3 (CI del olimpista)
            'fecha_nacimiento' => $fechaNacimiento,  // Fecha convertida
            'correo_electronico' => $row[4],  // Columna 5 (Correo electrónico del olimpista)
            'unidad_educativa' => $unidadEducativa,  // Columna 8 (Unidad Educativa como string)
            'id_grado' => $row[8],  // Columna 9 (Grado)
            'ci_tutor' => $row[11],  // Columna 12 (CI del tutor)
        ];
    }

    /**
     * Convertir un número de fecha de Excel a un formato YYYY-MM-DD
     *
     * @param int $excelDate
     * @return string
     */
    private static function excelDateToDateString(int $excelDate): string
    {
        // Excel usa un sistema de fechas basado en números, donde 1 es el 1 de enero de 1900.
        // Para convertirlo a formato de fecha en PHP, usamos Carbon para manipular las fechas.
        $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelDate - 2);  // -2 para ajustar al formato de Excel.
        
        return $carbonDate->format('Y-m-d');  // Devolver la fecha en formato 'YYYY-MM-DD'
    }
}
