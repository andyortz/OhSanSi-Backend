<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Ocr\OcrService;
use App\Services\OCR\PaymentVerificationService;
use App\Services\Ocr\OcrTextParser;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class PaymentSlipController extends Controller
{
    protected $ocrService;
    protected $validador;
    protected $parser;

    public function __construct(OcrService $ocrService, PaymentVerificationService $validador, OcrTextoParser $parser)
    {
        $this->ocrService = $ocrService;
        $this->validador = $validador;
        $this->parser = $parser;
    }

    public function process(Request $request): Response
    {
        $request->validate([
            'paymentSlip' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'id_list' => 'required|integer|exists:enrollment_list,id_list',
        ]);

        $idList = $request->input('id_list');
        $relativePath = $request->file('boleta')->store('boletas', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);

        try {
            $rawText = $this->ocrService->extracTexto($absolutePath);
            $fields = $this->parser->parse($rawText);

            // Agregar id_lista al array de datos OCR para validación
            $fields['id_list'] = $idList;

            // Verificar pago
            $verification = $this->validador->verifyPaymentOCR($fields);

        } catch (\Throwable $e) {
            Storage::disk('public')->delete($relativePath);

            return response()->json([
                'message' => 'Error durante el procesamiento OCR',
                'error'   => $e->getMessage(),
            ], 422);
        }

        Storage::disk('public')->delete($relativePath);

        return response()->json([
            'payment_verification' => $verificacion,
        ]);
    }
}
