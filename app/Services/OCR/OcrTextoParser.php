<?php

namespace App\Services\Ocr;

use Illuminate\Support\Str;

class OcrTextoParser
{
    public function parse(string $texto): array
    {
        $normalizado = Str::of($texto)->replace(["\r\n", "\r"], "\n")->toString();

        $fields = [
            'numero_recibo'   => $this->match('/N[úu]m[\.°o]*\s*:?[\s\n]*([0-9]{5,})/i', $normalizado),
            'nro_control'     => $this->match('/Nro\s*Control\s*:?[\s\n]*([0-9]+)/i', $normalizado),
            'fecha_hora'      => $this->match('/(\d{1,2}-\d{1,2}-\d{2,4}\s+\d{2}:\d{2})/', $normalizado),
            'usuario'         => $this->match('/Usuario\s*:?[\s\n]*([A-ZÁÉÍÓÚÑ ]{3,})/i', $normalizado),
            'recibi_de'       => $this->match('/Recib[ií]o?\s*(?:de|LE)\s*:?[\s\n]*([A-ZÁÉÍÓÚÑ ]{3,})/i', $normalizado),
            'concepto'        => $this->match('/concepto\s+de\s*:?[\s\n]*(.+?)(?:\n|$)/i', $normalizado),
            'importe_literal' => $this->match('/La\s+suma\s+de\s*:?[\s\n]*([A-ZÁÉÍÓÚÑ ]{3,})/i', $normalizado),

            'importe_total'   => OcrExtractors::importeTotal($normalizado),
            'documento'       => OcrExtractors::documento($normalizado),
            'aclaracion'      => OcrExtractors::aclaracion($normalizado),

            'codigo'          => $this->match('/C[oó]digo\s*:?[\s\n]*([0-9]{5,})/i', $normalizado),
        ];

        return $fields;
    }

    private function match(string $pattern, string $text): ?string
    {
        return preg_match($pattern, $text, $m) ? trim($m[1]) : null;
    }
}
