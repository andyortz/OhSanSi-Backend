<?php

namespace App\Services\ImportHelpers;

use App\Models\NivelCategoria;

class NivelResolver
{
    public static function resolve(string $nombreNivel): ?int
    {
        //intentamos encontrar el nivel por su nombre y id_area
        $nivel = NivelCategoria::where('nombre', 'ilike', trim($nombreNivel))
            ->first();
        
        return $nivel?->id_nivel ?? null;
    }
}
