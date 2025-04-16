<?php

namespace App\Services\ExcelProcessing;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\OlimpistaController;
use Illuminate\Http\Request;

class OlimpistaRegistrationService
{
    /**
     * Registrar un olimpista a través del API y devolver el ID del olimpista.
     *
     * @param array $olimpista
     * @return int|null
     */
    public static function registerOlimpista(array $olimpista): ?int
    {
        try {
            // Crear una instancia del controlador OlimpistaController
            $olimpistaController = new OlimpistaController();
            
            // Convertir el array de datos a un objeto Request y llamar al método store
            $response = $olimpistaController->store(new Request($olimpista));

            // Si la respuesta es exitosa
            if ($response->status() === 201) {
                Log::info("Olimpista registered successfully.", $olimpista);
                return $response->json('olimpista.id_olimpista');  // Retornar el ID del olimpista registrado
            }

            Log::error("Error registering olimpista. Response: " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("Exception during olimpista registration: " . $e->getMessage());
            return null;
        }
    }
}
