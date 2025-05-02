<?php

namespace App\Services\ImportHelpers;

class GradoResolver
{
    private static $grados = [
        '1ro de Primaria' => 1,
        '2do de Primaria' => 2,
        '3ro de Primaria' => 3,
        '4to de Primaria' => 4,
        '5to de Primaria' => 5,
        '6to de Primaria' => 6,
        '1ro de Secundaria' => 7,
        '2do de Secundaria' => 8,
        '3ro de Secundaria' => 9,
        '4to de Secundaria' => 10,
        '5to de Secundaria' => 11,
        '6to de Secundaria' => 12,
    ];

    public static function resolve(string $text): ?int
    {
        return self::$grados[trim($text)] ?? null;
    }
}
