<?php

namespace App\Services\OCR;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OcrService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OCR_SPACE_API_KEY');
    }

    public function extractText(string $imagePath): string
    {
        $response = Http::attach(
                'file', file_get_contents($imagePath), basename($imagePath)
            )
            ->post('https://api.ocr.space/parse/image', [
                'apikey' => $this->apiKey,
                'language' => 'spa',
                'isOverlayRequired' => 'false',
                'OCREngine' => 2,
            ]);

        $data = $response->json();

        if (
            $data['OCRExitCode'] !== 1 ||
            ($data['IsErroredOnProcessing'] ?? true) ||
            empty($data['ParsedResults'][0]['ParsedText'])
        ) {
            $error = $data['ErrorMessage'][0] ?? 'OCR.space no devolvió texto o ocurrió un error';
            throw new \RuntimeException("Error durante el procesamiento OCR: $error");
        }

        return $data['ParsedResults'][0]['ParsedText'];
    }
}
