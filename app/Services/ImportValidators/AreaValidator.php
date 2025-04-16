<?php
namespace App\Services\ImportValidators;

use App\Services\ImportHelpers\AreaResolver;

class AreaValidator
{
    public static function validateAreas(
        string $area1,
        ?string $area2,
        int $maxAreas,
        array $validAreas
    ): ?array {
        // Trim spaces and make the areas lowercase
        $area1 = trim($area1);
        $area2 = $area2 ? trim($area2) : null;

        // Validate the first area
        if (!AreaResolver::isValid($area1, $validAreas)) {
            logger()->error("Invalid area 1: '$area1'.");
            return null;
        }

        // Validate the second area if it exists
        if ($area2 && !AreaResolver::isValid($area2, $validAreas)) {
            logger()->error("Invalid area 2: '$area2'.");
            return null;
        }

        // Check if areas 1 and 2 are the same
        if ($area1 && $area2 && strtolower($area1) === strtolower($area2)) {
            logger()->error("Area 1 and area 2 cannot be the same.");
            return null;
        }

        // Count selected areas (if area2 is null, only area1 counts)
        $areasSelected = array_filter([$area1, $area2]);
        if (count($areasSelected) > $maxAreas) {
            logger()->error("Exceeded the maximum allowed areas: $maxAreas.");
            return null;
        }

        // Return sanitized areas
        return [
            'area_1' => $area1,
            'area_2' => $area2,
        ];
    }
}
