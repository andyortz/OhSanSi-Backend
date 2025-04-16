<?php

namespace App\Services\ExcelProcessing;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TutorRegistrationService
{
    /**
     * Registrar un tutor a través del API y devolver el ID del tutor.
     *
     * @param array $tutor
     * @return int|null
     */
    public static function registerTutor(array $tutor): ?int
    {
        try {
            // Enviar solicitud POST al endpoint de tutores
            $response = Http::post('http://localhost:8000/api/tutores', $tutor);

            if ($response->successful()) {
                Log::info("Tutor registered successfully.", $tutor);
                return $response->json('id_tutor'); // Retornar el ID del tutor
            }

            if ($response->status() === 400 && $response->json('id_tutor')) {
                Log::info("Tutor already exists.", $tutor);
                return $response->json('id_tutor'); // Si el tutor ya existe, retornar su ID
            }

            Log::error("Error registering tutor. Response: " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("Exception during tutor registration: " . $e->getMessage());
            return null;
        }
    }
}
