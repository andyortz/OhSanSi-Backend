<?php

namespace App\Services\ExcelProcessing;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExcelService
{
    /**
     * Register a tutor via API and return the tutor ID.
     */
    public static function registerTutor(array $tutor): ?int
    {
        try {
            $url = config('services.excel_api.url') . '/tutores';
            $response = Http::post($url, $tutor);

            if ($response->status() === 201) {
                return $response->json('tutor.id_tutor');
            }

            if ($response->status() === 400 && $response->json('id_tutor')) {
                return $response->json('id_tutor');
            }

            Log::error("Tutor registration failed. Response: " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("Exception during tutor registration: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Register an olympian with the given tutor ID. (To be implemented)
     */
    public static function registerOlympian(array $olympian, int $tutorId): ?int
    {
        // TODO: Implement logic to register olympian and return olympian ID
        return null;
    }

    /**
     * Register an area and category for the olympian. (To be implemented)
     */
    public static function registerAreaCategory(int $olympiadId, int $olympianId, array $inscription): void
    {
        // TODO: Implement logic to register area/category per olympian
    }
}
