<?php

use App\Modules\Olympists\Controllers\OlympistController;

Route::prefix('olympists')->group(function () {
    Route::post('/register', [OlympistController::class, 'register']); // Registro
    Route::post('/{id}/upload-payment', [OlympistController::class, 'uploadPayment']); // Subir boleta
});