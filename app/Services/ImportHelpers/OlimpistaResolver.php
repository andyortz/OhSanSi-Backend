<?php

namespace App\Services\ImportHelpers;

use Carbon\Carbon;


class OlimpystResolver
{
    /**
     * Extraer los datos del olimpista desde la fila del Excel.
     * 
     * @param array $row
     * @param int $fila
     * @return array
     */
    public static function extractOlimpystaData(array $row, array &$answerFinal): array
    {
        // Convertir la unidad educativa a string
        $school =$row[7];

        // Procesar la fecha de nacimiento
        $birthdate = self::normalizeDate($row[3], $row[21]);

        return [
            'names' => $row[0],
            'lastNames' => $row[1],
            'ci' => $row[2],
            'birthdate' => $birthdate,
            'email' => $row[4],
            'region' => $row[5],
            'province' => $row[6],
            'school' => $school,
            'id_grade' => $row[8],
            'ci_tutor' => $row[11],
            'row' => $row[21],
        ];
    }

    private static function normalizeDate($value, $row): string
{
    // Si es numérico tipo Excel
    if (is_numeric($value)) {
        return self::excelDateToDateString((int) $value);
    }

    // Lista de formats aceptados
    $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y'];

    foreach ($formats as $format) {
        $date = \DateTime::createFromFormat($format, $value);
        if ($date && $date->format($format) === $value) {
            return $date->format('Y-m-d');
        }
    }

    // Si nada funciona, registrar error
    $answerFinal['olimpysts_errors'][] = [
        'message' => "format de fecha no válido",
        'row' => $row + 2
    ];

    return "0000-00-00"; // Value por defecto para no romper la lógica
}


    private static function excelDateToDateString(int $excelDate): string
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelDate - 2);
        return $carbonDate->format('Y-m-d');
    }
}
