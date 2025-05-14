<?php

namespace App\Services\Ocr;

use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Str;

/**
 * Servicio encargado de extraer y estructurar la información de un
 * "Recibo de Caja" como el que usa la UMSS.  
 *
 * Paso general:
 * 1. Recibe la ruta absoluta de la imagen ya almacenada (jpg, png, etc.)
 * 2. Ejecuta Tesseract para obtener todo el texto.
 * 3. Aplica expresiones regulares para obtener los campos clave.
 * 4. Devuelve un array listo para persistir o responder en JSON.
 */
class OcrService
{
    /**
     * Analiza un recibo de caja y devuelve los campos estructurados.
     *
     * @param  string  $imagePath  Ruta absoluta de la imagen.
     * @return array  [raw_text => string, fields => array]
     * @throws \RuntimeException Si Tesseract no devuelve texto.
     */
    public function analizarReciboCaja(string $imagePath): array
    {
        // --- 1. Ejecutar OCR ---
        $texto = (new TesseractOCR($imagePath))
            ->lang('spa')         // español
            ->psm(6)              // Assume a single uniform block of text
            ->run();

        if (Str::of($texto)->trim()->isEmpty()) {
            throw new \RuntimeException('OCR no devolvió texto');
        }

        // --- 2. Normalizar saltos de línea para facilitar los regex ---
        $normalizado = Str::of($texto)->replace(["\r\n", "\r"], "\n")->toString();

        // --- 3. Extraer campos con regex ---
        $fields = [
            'numero_recibo'   => $this->firstMatch('/Nro\.?\s*[:]?\s*(\d{4,})/i', $normalizado),
            'nro_control'     => $this->firstMatch('/Nro\.\s*Control\s*[:]?\s*(\d{2,})/i', $normalizado),
            'fecha_hora'      => $this->firstMatch('/Fecha\s*[:]?\s*(\d{2}-\d{2}-\d{2}\s+\d{2}:\d{2})/i', $normalizado),
            'usuario'         => $this->firstMatch('/Usuario\s*[:]?\s*([A-ZÁÉÍÓÚÑ ]{3,})/i', $normalizado),
            'recibi_de'       => $this->firstMatch('/Recib[ií]\s*de\s*[:]?\s*([A-ZÁÉÍÓÚÑ ]{3,})/i', $normalizado),
            'concepto'        => $this->firstMatch('/Por\s+concepto\s+de\s*[:]?\s*(.+?)\n/i', $normalizado),
            'importe_literal' => $this->firstMatch('/La\s+suma\s+de\s*[:]?\s*([A-ZÁÉÍÓÚÑ ]+)/i', $normalizado),
            'importe_total'   => $this->firstMatch('/Total\s*[:]?\s*Bs\.?\s*(\d+[\.,]?\d{0,2})/i', $normalizado),
            'documento'       => $this->firstMatch('/Documento\s*[:]?\s*(\d{5,})/i', $normalizado),
            'codigo'          => $this->firstMatch('/Codig[oo]\s*[:]?\s*(\d{5,})/i', $normalizado),
        ];

        return [
            'raw_text' => $texto,
            'fields'   => $fields,
        ];
    }

    /**
     * Devuelve la primera coincidencia o null.
     */
    private function firstMatch(string $pattern, string $subject): ?string
    {
        if (preg_match($pattern, $subject, $m)) {
            return trim($m[1]);
        }
        return null;
    }
}
