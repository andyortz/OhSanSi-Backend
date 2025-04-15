<?php

namespace App\Services\ImportHelpers;


use App\Services\OlimpiadaService;
use App\Models\NivelAreaOlimpiada;



class AreaResolver
{
    /**
     * Consulta el endpoint para obtener la olimpiada abierta
     */
    public static function getOlimpiadaAbiertaId(): ?int
    {
        $idOlimpiada = OlimpiadaService::getOlimpiadaAbierta()?->id_olimpiada;

        if (!$idOlimpiada) {
            logger()->error("No hay olimpiada activa.");
            return null;
        }

        return $idOlimpiada;
    }

    /**
     * Devuelve una lista de nombres de áreas para la olimpiada
     */
    public static function getValidAreas(int $idOlimpiada): array
    {
        return NivelAreaOlimpiada::with('area')
        ->where('id_olimpiada', $idOlimpiada)
        ->get()
        ->pluck('area.nombre')
        ->map(fn($a) => strtolower(trim($a)))
        ->unique()
        ->values()
        ->toArray();
    }

    public static function isValid(string $nombreArea, array $validAreas): bool
    {
        return in_array(strtolower(trim($nombreArea)), $validAreas);
    }
}
