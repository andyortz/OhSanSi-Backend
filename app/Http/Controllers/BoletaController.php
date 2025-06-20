<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\OCR\OcrService;
use App\Services\OCR\VerificacionPagoService;
use Symfony\Component\HttpFoundation\Response;
use App\Services\OCR\OcrTextoParser;
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
            'voucher' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'list_id' => 'required|integer|exists:lista_inscripcion,id_lista',
        ]);

        $idLista = $request->input('list_id');
        $relativePath = $request->file('voucher')->store('voucher', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);

        try {
            $rawText = $this->ocrService->extraerTexto($absolutePath);
            $fields = $this->parser->parse($rawText);

            // Agregar list_id al array de datos OCR para validación
            $fields['list_id'] = $idLista;

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
