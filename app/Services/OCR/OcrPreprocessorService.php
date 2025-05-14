<?php

namespace App\Services\Ocr;

use \Imagick;
use \ImagickException;

class OcrImagePreprocessorService
{
    /**
     * Procesa una imagen para optimizar su reconocimiento OCR con Tesseract.
     *
     * @param string $inputPath Ruta de la imagen original.
     * @param string $outputPath Ruta donde se guardará la imagen procesada.
     * @throws ImagickException
     */
    public function procesarImagen(string $inputPath, string $outputPath): void
    {
        $imagick = new Imagick($inputPath);

        // Convertir a escala de grises
        $imagick->setImageColorspace(Imagick::COLORSPACE_GRAY);

        // Aumentar DPI a 300
        $imagick->setImageResolution(300, 300);
        $imagick->resampleImage(300, 300, Imagick::FILTER_LANCZOS, 1);

        // Binarización (Umbral adaptativo estilo Otsu)
        $imagick->adaptiveThresholdImage(15, 15, 10);

        // Aplicar un blur suave para reducir ruido (radio 1, sigma 0.5)
        $imagick->blurImage(1, 0.5);

        // Aplicar sharpen para aumentar la nitidez (radius 0, sigma 1)
        $imagick->sharpenImage(0, 1);

        // Opcional: Deskew si la imagen está inclinada (threshold 40%)
        $imagick->deskewImage(40);

        // Establecer formato de salida
        $imagick->setImageFormat('png');

        // Guardar la imagen procesada
        $imagick->writeImage($outputPath);

        // Liberar recursos
        $imagick->clear();
        $imagick->destroy();
    }
}
