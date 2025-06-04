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
            'id_lista' => 'required|integer|exists:lista_inscripcion,id_lista',
        ]);

        $idLista = $request->input('id_lista');
        $relativePath = $request->file('boleta')->store('boletas', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);

        try {
            $rawText = $this->ocrService->extraerTexto($absolutePath);
            $fields = $this->parser->parse($rawText);

            // Agregar id_lista al array de datos OCR para validación
            $fields['id_lista'] = $idLista;

            // Verificar pago
            $verificacion = $this->validador->verificarPagoOCR($fields);

        } catch (\Throwable $e) {
            Storage::disk('public')->delete($relativePath);

            return response()->json([
                'message' => 'Error durante el procesamiento OCR',
                'error'   => $e->getMessage(),
            ], 422);
        }

        Storage::disk('public')->delete($relativePath);

        return response()->json([
            'verificacion_pago' => $verificacion,
        ]);
    }
}
