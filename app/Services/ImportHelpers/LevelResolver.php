<?php

namespace App\Services\ImportHelpers;

use App\Modules\Olympiad\Models\CategoryLevel;

class LevelResolver
{
    public static function resolve(string $levelName): ?int
    {
        //intentamos encontrar el nivel por su nombre y id_area
        $level = CategoryLevel::where('name', 'ilike', trim($levelName))
            ->first();
        
        return $level?->id_level ?? null;
    }
}
