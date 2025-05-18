<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Ocr\OcrService;
use Symfony\Component\HttpFoundation\Response;

class BoletaController extends Controller
{
    protected $ocrService;

    public function __construct(OcrService $ocrService)
    {
        $this->ocrService = $ocrService;
    }

    public function procesar(Request $request): Response
    {
        $request->validate([
            'boleta' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $relativePath = $request->file('boleta')->store('boletas', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);

        try {
            // Ejecutar OCR directamente sobre la imagen recibida
            $resultado = $this->ocrService->analizarReciboCaja($absolutePath);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($relativePath);

            return response()->json([
                'message' => 'Error durante el procesamiento OCR',
                'error'   => $e->getMessage(),
            ], 422);
        }

        // Eliminar la imagen después del procesamiento
        Storage::disk('public')->delete($relativePath);

        return response()->json([
            'data' => $resultado['fields'],
            'raw'  => $resultado['raw_text'],
        ]);
    }
}
