<?php

namespace App\Modules\Enrollments\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\OCR\OcrService;
use App\Services\OCR\PaymentVerificationService;
use Symfony\Component\HttpFoundation\Response;
use App\Services\OCR\OcrTextParser;
use Illuminate\Support\Facades\Log;

class PaymentSlipController
{
    protected $ocrService;
    protected $validador;
    protected $parser;

    public function __construct(OcrService $ocrService, PaymentVerificationService $validador, OcrTextParser $parser)
    {
        $this->ocrService = $ocrService;
        $this->validador = $validador;
        $this->parser = $parser;
    }

    public function process(Request $request): Response
    {
        $request->validate([
            'voucher' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'list_id' => 'required|integer|exists:enrollment_list,list_id',
        ]);

        $listId = $request->input('list_id');
        $relativePath = $request->file('voucher')->store('vouchers', 'public');
        $absolutePath = storage_path('app/public/' . $relativePath);
        //
        try {
            $rawText = $this->ocrService->extractText($absolutePath);
            $fields = $this->parser->parse($rawText);

            // Agregar id_lista al array de datos OCR para validación
            $fields['list_id'] = $listId;

            // Verificar pago
            $verificacion = $this->validador->verifyPaymentOCR($fields);

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
