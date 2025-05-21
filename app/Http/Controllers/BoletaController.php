<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Ocr\OcrService;
use App\Services\OCR\VerificacionPagoService;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Ocr\OcrTextoParser;
use Illuminate\Support\Facades\Log;

class BoletaController extends Controller
{
    protected $ocrService;
    protected $validador;
    protected $parser;

    public function __construct(OcrService $ocrService, VerificacionPagoService $validador, OcrTextoParser $parser)
    {
        $this->ocrService = $ocrService;
        $this->validador = $validador;
        $this->parser = $parser;
    }

    public function procesar(Request $request): Response
    {
        $request->validate([
            'boleta' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $relativePath = $request->file('boleta')->store('boletas', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);

        try {
            $rawText = $this->ocrService->extraerTexto($absolutePath);
            $fields = $this->parser->parse($rawText);

            // Verificar pago si tenemos documento y total
            $verificacion = null;
            if (!empty($fields['documento']) && !empty($fields['importe_total'])) {
                $verificacion = $this->validador->verificarPagoOCR([
                    'documento' => $fields['documento'],
                    'importe_total' => $fields['importe_total']
                ]);
            }

        } catch (\Throwable $e) {
            Storage::disk('public')->delete($relativePath);

            return response()->json([
                'message' => 'Error durante el procesamiento OCR',
                'error'   => $e->getMessage(),
            ], 422);
        }

        Storage::disk('public')->delete($relativePath);

        return response()->json([
            'data' => $fields,
            'verificacion_pago' => $verificacion,
            'raw' => $rawText,
        ]);
    }
}
