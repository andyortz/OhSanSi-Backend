<?php

namespace App\Services\ImportHelpers;

class DepartamentoResolver
{
    private static $departamentos = [
        'Chuquisaca' => 1,
        'La Paz' => 2,
        'Cochabamba' => 3,
        'Oruro' => 4,
        'Potosí' => 5,
        'Tarija' => 6,
        'Santa Cruz' => 7,
        'Beni' => 8,
        'Pando' => 9,
    ];

    public static function resolve(string $text): ?int
    {
        return self::$departamentos[trim($text)] ?? null;
    }
}
