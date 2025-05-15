<?php

namespace App\Services\Ocr;

use Imagick;
use ImagickException;
use Illuminate\Support\Facades\Log;

class OcrImagePreprocessorService
{
    public function procesarImagen(string $inputPath, string $outputPath): void
    {
        try {
            Log::info("Iniciando preprocesamiento de imagen: {$inputPath}");

            $imagick = new Imagick($inputPath);

            Log::info("Imagen cargada correctamente: {$inputPath}");

            // Convertir a escala de grises
            $imagick->setImageColorspace(Imagick::COLORSPACE_GRAY);
            Log::info("Convertido a escala de grises");

            // Binarización (umbral fijo 128 - blanco/negro puro)
            $imagick->adaptiveThresholdImage(15, 15, 10);
            Log::info("Aplicada binarización umbral 128");

            // Establecer formato de salida
            $imagick->setImageFormat('png');
            Log::info("Formato de salida establecido a PNG");

            // Guardar la imagen procesada
            $imagick->writeImage($outputPath);
            Log::info("Imagen procesada guardada en: {$outputPath}");

            // Liberar recursos
            $imagick->clear();
            $imagick->destroy();
            Log::info("Recursos liberados correctamente");

        } catch (ImagickException $e) {
            Log::error("Error en Imagick durante preprocesamiento: {$e->getMessage()}");
            throw $e; // Lo relanzamos para que el Controller capture el fallo
        } catch (\Throwable $e) {
            Log::error("Error inesperado durante preprocesamiento: {$e->getMessage()}");
            throw $e; // Cualquier otro error inesperado
        }
    }
}
