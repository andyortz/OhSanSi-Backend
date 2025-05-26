<?php

namespace App\Services\Ocr;

class OcrExtractors
{
    public static function importeTotal(string $text): ?float
    {
        if (preg_match('/T[O0]?[R ]?A?L[:.\s]*\n?\s*([0-9]{1,4}[.,][0-9]{2})/iu', $text, $m)) {
            return (float) str_replace(',', '.', $m[1]);
        }

        if (preg_match('/(?:B[S5]\.?|BOLI\w*)\s*\n?\s*([0-9]{1,4}[.,][0-9]{2})/iu', $text, $m)) {
            return (float) str_replace(',', '.', $m[1]);
        }

        preg_match_all('/[0-9]{1,4}[.,][0-9]{2}/', $text, $all);
        if ($all[0]) {
            $nums = array_map(fn($n) => (float) str_replace(',', '.', $n), $all[0]);
            return max($nums);
        }

        return null;
    }

    public static function documento(string $text): ?string
    {
        if (preg_match('/Documen\w{0,4}\s*[:.\-]?\s*\n?\s*([0-9]{6,})/iu', $text, $m)) {
            return $m[1];
        }

        if (preg_match('/\b([0-9]{7,})\b/', $text, $m)) {
            return $m[1];
        }

        return null;
    }

    public static function aclaracion(string $text): ?string
    {
        // Caso 3: "N° PAGO-xxxx"
        if (preg_match('/N[°º]?\s*PAGO[\s\-:]*([A-Za-z0-9]+)/iu', $text, $m)) {
            return 'PAGO-' . trim($m[1]);
        }
        // Caso 1: "Aclaración: ..." en la misma línea
        if (preg_match('/Aclaraci[oó]n\s*[:\-]?\s*([^\n]+)/iu', $text, $m)) {
            return trim($m[1]);
        }

        // Caso 2: "Aclaración:\n ..." en la línea siguiente
        if (preg_match('/Aclaraci[oó]n\s*[:\-]?\s*\n\s*([^\n]+)/iu', $text, $m)) {
            return trim($m[1]);
        }

        

        return null;
    }

}
