<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Ocr\OcrService;
use Symfony\Component\HttpFoundation\Response;

class BoletaController extends Controller
{
    /** @var OcrService */
    protected $ocrService;

    public function __construct(OcrService $ocrService)
    {
        $this->ocrService = $ocrService;
    }

    /**
     * Endpoint POST /boletas/ocr
     * Recibe la imagen de un recibo de caja y devuelve los datos OCR.
     */
    public function procesar(Request $request): Response
    {
        // 1. Validar la solicitud
        $request->validate([
            'boleta' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // 2. Almacenar temporalmente la imagen en disk "public"
        $relativePath = $request->file('boleta')->store('boletas', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);

        try {
            // 3. Delegar al servicio OCR
            $resultado = $this->ocrService->analizarReciboCaja($absolutePath);
        } catch (\Throwable $e) {
            // Limpieza del archivo y respuesta de error
            Storage::disk('public')->delete($relativePath);
            return response()->json([
                'message' => 'Error durante el OCR',
                'error'   => $e->getMessage(),
            ], 422);
        }

        // 4. (Opcional) eliminar la imagen una vez procesada
        Storage::disk('public')->delete($relativePath);

        // 5. Responder con los datos extraídos
        return response()->json([
            'data' => $resultado['fields'],
            'raw'  => $resultado['raw_text'],
        ]);
    }
}
