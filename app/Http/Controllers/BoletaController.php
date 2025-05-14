<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Ocr\OcrService;
use App\Services\Ocr\OcrImagePreprocessorService;
use Symfony\Component\HttpFoundation\Response;

class BoletaController extends Controller
{
    protected $ocrService;
    protected $preprocessorService;

    public function __construct(OcrService $ocrService, OcrImagePreprocessorService $preprocessorService)
    {
        $this->ocrService = $ocrService;
        $this->preprocessorService = $preprocessorService;
    }

    public function procesar(Request $request): Response
    {
        $request->validate([
            'boleta' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $relativePath = $request->file('boleta')->store('boletas', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);

        // Preparar la ruta para la imagen optimizada
        $processedPath = storage_path('app/public/boletas/processed_' . basename($relativePath));

        try {
            // 1. Pre-procesar la imagen antes del OCR
            $this->preprocessorService->procesarImagen($absolutePath, $processedPath);

            // 2. Ejecutar OCR sobre la imagen procesada
            $resultado = $this->ocrService->analizarReciboCaja($processedPath);
        } catch (\Throwable $e) {
            // Limpieza de archivos
            Storage::disk('public')->delete($relativePath);
            Storage::disk('public')->delete('boletas/processed_' . basename($relativePath));

            return response()->json([
                'message' => 'Error durante el procesamiento OCR',
                'error'   => $e->getMessage(),
            ], 422);
        }

        // Limpiar imágenes temporales
        Storage::disk('public')->delete($relativePath);
        Storage::disk('public')->delete('boletas/processed_' . basename($relativePath));

        return response()->json([
            'data' => $resultado['fields'],
            'raw'  => $resultado['raw_text'],
        ]);
    }
}
