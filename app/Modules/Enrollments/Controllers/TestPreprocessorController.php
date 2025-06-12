<?php

namespace App\Modules\Enrollments\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\OCR\OcrImagePreprocessorService;
use Symfony\Component\HttpFoundation\Response;

class TestPreprocessorController
{
    protected $preprocessorService;

    public function __construct(OcrImagePreprocessorService $preprocessorService)
    {
        $this->preprocessorService = $preprocessorService;
    }

    public function test(Request $request): Response
    {
        $request->validate([
            'boleta' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $relativePath = $request->file('boleta')->store('boletas', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);

        $processedPath = storage_path('app/public/boletas/processed_' . basename($relativePath));

        try {
            Log::info("Iniciando preprocesamiento de prueba: {$absolutePath}");

            // Procesar imagen
            $this->preprocessorService->procesarImagen($absolutePath, $processedPath);

            // Verificar si la imagen procesada existe
            if (!file_exists($processedPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La imagen procesada no se creó correctamente.',
                ], 500);
            }

            // Obtener base64 de la imagen procesada (opcional para frontend)
            $imageData = base64_encode(file_get_contents($processedPath));
            $mimeType = mime_content_type($processedPath);

            return response()->json([
                'success' => true,
                'message' => 'Preprocesamiento completado.',
                'processed_image_path' => $processedPath,
                'processed_image_base64' => 'data:' . $mimeType . ';base64,' . $imageData,
            ]);

        } catch (\Throwable $e) {
            Log::error('Error en TestPreprocessorController: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error durante el preprocesamiento.',
                'error' => $e->getMessage(),
            ], 500);
        } finally {
            // Limpiar archivos temporales
            Storage::disk('public')->delete($relativePath);
            Storage::disk('public')->delete('boletas/processed_' . basename($relativePath));
        }
    }
}
