<?php

use App\Modules\Olympists\Controllers\OlympistController;
use App\Modules\Olympists\Controllers\PersonController;
use App\Modules\Olympists\Controllers\DepartamentController;

Route::prefix('olympists')->middleware('throttle:100,1')->group(function () {
    Route::get('/{ci_olympist}/enrollments', [OlympistController::class, 'enrollments']);
    Route::get('/{ci_olympist}/areas-levels', [OlympistController::class, 'areasLevels']);
    

    Route::post('/register', [OlympistController::class, 'register']); // Registro
    Route::post('/{id}/upload-payment', [OlympistController::class, 'uploadPayment']); // Subir boleta
});

Route::prefix('person')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [PersonController::class, 'store']);
    Route::get('/{ci}', [PersonController::class, 'show']);
});

Route::prefix('province')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [DepartamentController::class, 'index']);
    Route::get('/{id_departament}', [DepartamentController::class, 'getDepartaments']);
});

Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']);
    Route::post('/registration', [ExcelDataController::class, 'cleanDates']);
});

Route::prefix('enrrolments')->middleware('throttle:100,1')->group(function () {
    Route::get('/enrrolments/participants/{id}',[EnrollmentListController::class, 'getById']);
    Route::get('/enrollments/{ci}/{estado}', [EnrollmentListController::class, 'obtenerPorResponsable'])
        ->where('status', 'PENDIENTE|PAGADO|TODOS');
    Route::get('/enrollments/pending/{ci}', [EnrollmentListController::class, 'listasPagoPendiente']);
    Route::get('/enrollments', [EnrollmentListController::class, 'index']);
});

Route::get('/receipts/individual/{id}', [EnrollmentListController::class, 'individual']);

Route::get('/receipts/group/{id}', [EnrollmentListController::class, 'grupal']);
