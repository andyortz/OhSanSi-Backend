<?php

namespace App\Services\ImportValidators;

use App\Services\ImportHelpers\AreaResolver;

class AreaValidator
{
    public static function validarAreas(
        string $area1,
        ?string $area2,
        array $areasValidas,
        int $maxAreas,
        int $index,
        int $idOlimpiada
    ): ?array {
        $area1 = trim($area1);
        $area2 = $area2 ? trim($area2) : null;

        if (!AreaResolver::isValid($area1, $areasValidas)) {
            logger()->error("Fila $index: Área 1 '$area1' no válida para la olimpiada $idOlimpiada");
            return null;
        }

        if ($area2 && !AreaResolver::isValid($area2, $areasValidas)) {
            logger()->error("Fila $index: Área 2 '$area2' no válida para la olimpiada $idOlimpiada");
            return null;
        }

        if ($area1 && $area2 && strtolower($area1) === strtolower($area2)) {
            logger()->error("Fila $index: Las áreas 1 y 2 no pueden ser iguales.");
            return null;
        }

        $areasSeleccionadas = array_filter([$area1, $area2]);
        if (count($areasSeleccionadas) > $maxAreas) {
            logger()->error("Fila $index: Excede el límite de $maxAreas áreas permitidas.");
            return null;
        }

        return [
            'area_1' => $area1,
            'area_2' => $area2,
        ];
    }
}
