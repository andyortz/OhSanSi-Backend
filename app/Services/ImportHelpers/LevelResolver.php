<?php

namespace App\Services\ImportHelpers;

use App\Modules\Olympiads\Models\CategoryLevel;

class LevelResolver
{
    public static function resolve(string $levelName): ?int
    {
        //intentamos encontrar el nivel por su nombre y id_area
        $level = CategoryLevel::where('level_name', 'ilike', trim($levelName))
            ->first();
        
        return $level?->level_id ?? null;
    }
}
