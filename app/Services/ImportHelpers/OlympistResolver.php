<?php

namespace App\Services\ImportHelpers;

use Carbon\Carbon;


class OlympistResolver
{
    /**
     * Extraer los datos del olimpista desde la fila del Excel.
     * 
     * @param array $row
     * @param int $fila
     * @return array
     */

    

    public static function extractOlympistData(array $row, array &$resultado): array
    {
        // Convertir la unidad educativa a string
        $unidadEducativa =$row[7];

        // Procesar la fecha de nacimiento
        $fechaNacimiento = self::normalizeDate($row[3], $fila);

        return [
            'names' => self::normalizeText($row[0]),
            'surnames' => self::normalizeText($row[1]),
            'olympist_ci' => $row[2],
            'birthdate' => $fechaNacimiento,
            'email' => $row[4],
            'department' => $row[5],
            'province' => $row[6],
            'school' => $unidadEducativa,
            'grade_id' => $row[8],
            'tutor_ci' => $row[11],
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

    private static function normalizeDate($value, $index): string
    {
        // Si es numérico tipo Excel
        if (is_numeric($value)) {
            return self::excelDateToDateString((int) $value);
        }

        // Lista de formatos aceptados
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y'];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date && $date->format($format) === $value) {
                return $date->format('Y-m-d');
            }
        }

        // Si nada funciona, registrar error
        $resultado['olympists_errors'][] = [
            'ci' => 'Desconocido',
            'message' => "Formato de fecha no válido",
            'row' => $index + 2
        ];

        return "00-00-0000"; // Valor por defecto para no romper la lógica
    }

    private static function excelDateToDateString(int $excelDate): string
    {
        $carbonDate = Carbon::createFromFormat('d-m-Y', '01-01-1900')->addDays($excelDate - 2);
        return $carbonDate->format('d-m-Y');
    }
}
