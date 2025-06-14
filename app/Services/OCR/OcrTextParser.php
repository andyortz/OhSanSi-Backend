<?php

namespace App\Services\OCR;

use Illuminate\Support\Str;

class OcrTextParser
{
    public function parse(string $text): array
    {
        $normalized = Str::of($text)->replace(["\r\n", "\r"], "\n")->toString();

        $fields = [
            'voucher_nro'   => $this->match('/N[úu]m[\.°o]*\s*:?[\s\n]*([0-9]{5,})/i', $normalized),
            'control_nro'     => $this->match('/Nro\s*Control\s*:?[\s\n]*([0-9]+)/i', $normalized),
            'hour_date'      => $this->match('/(\d{1,2}-\d{1,2}-\d{2,4}\s+\d{2}:\d{2})/', $normalized),
            'user'         => $this->match('/Usuario\s*:?[\s\n]*([A-ZÁÉÍÓÚÑ ]{3,})/i', $normalized),
            'received_from'       => $this->match('/Recib[ií]o?\s*(?:de|LE)\s*:?[\s\n]*([A-ZÁÉÍÓÚÑ ]{3,})/i', $normalized),
            'concept'        => $this->match('/concepto\s+de\s*:?[\s\n]*(.+?)(?:\n|$)/i', $normalized),
            'literal_amount' => $this->match('/La\s+suma\s+de\s*:?[\s\n]*([A-ZÁÉÍÓÚÑ ]{3,})/i', $normalized),

            'total_import'   => OcrExtractors::totalAmount($normalized),
            'document'       => OcrExtractors::document($normalized),
            'clarification'      => OcrExtractors::clarification($normalized),

            'code'          => $this->match('/C[oó]digo\s*:?[\s\n]*([0-9]{5,})/i', $normalized),
        ];

        return $fields;
    }

    private function match(string $pattern, string $text): ?string
    {
        return preg_match($pattern, $text, $m) ? trim($m[1]) : null;
    }
}
