<?php

use App\Modules\Olympists\Controllers\OlympistController;
use App\Modules\Olympists\Controllers\PersonController;
use App\Modules\Olympists\Controllers\DepartamentController;
use App\Modules\Olympists\Controllers\EnrollmentController;

Route::prefix('olympists')->middleware('throttle:100,1')->group(function () {
    Route::get('/{ci}/enrollments', [OlympistController::class, 'enrollments']);
    Route::get('/{ci}/enrollments', [EnrollmentController::class, 'getEnrollmentsByCi']);
    Route::get('/{ci}/areas-levels', [OlympistController::class, 'areasLevels']);

    Route::get('/id/{ci_olympist}', [OlympistController::class, 'getByCedula']);
    Route::post('/', [OlympistController::class, 'store']);

    Route::post('/register', [OlympistController::class, 'register']); // Registro
    Route::post('/{id}/upload-payment', [OlympistController::class, 'uploadPayment']); // Subir boleta
});

Route::prefix('person')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [PersonController::class, 'store']);
    Route::get('/{ci}', [PersonController::class, 'show']);
});

Route::prefix('province')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [ProvinceController::class, 'index']); 
    Route::get('/', [DepartamentController::class, 'index']);
    Route::get('/{id_departament}', [DepartamentController::class, 'getDepartaments']);
    Route::get('/{id}', [ProvinciaController::class, 'porDepartamento']);
});

Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']);
    Route::post('/registration', [ExcelDataController::class, 'cleanDates']);
});

Route::prefix('enrrolments')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [EnrollmentListController::class, 'index']);
    Route::get('/{ci}/{estado}', [EnrollmentListController::class, 'obtenerPorResponsable'])
        ->where('status', 'PENDIENTE|PAGADO|TODOS');
    Route::get('/participants/{id}',[EnrollmentListController::class, 'getById']);
    Route::get('/pending/{ci}', [EnrollmentListController::class, 'listasPagoPendiente']);
    Route::post('/with-tutor', [LevelEnrollmentController::class, 'storeWithTutor']);
});

Route::prefix('receipts')->middleware('throttle:100,1')->group(function () {
    Route::get('/individual/{id}', [EnrollmentListController::class, 'individual']);
    Route::get('/group/{id}', [EnrollmentListController::class, 'grupal']);
});
Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']);
    Route::post('/registration', [ExcelDataController::class, 'cleanDates']);
});

Route::post('/tutors', [TutorsController::class, 'store']);
Route::get('/tutors/id/{ci}',[TutorsController::class,'searchByCi']);

Route::prefix('schools')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [SchoolController::class, 'index']);
    Route::get('/names', [SchoolController::class, 'justNames']); 
    Route::get('/provinces/{id}', [SchoolController::class, 'byProvince']);
});

Route::get('/departaments', [DepartamentController::class, 'index']);
Route::get('/enrollments/{ci}/{estado}', [EnrollmentListController::class, 'obtenerPorResponsable'])
    ->where('status', 'PENDIENTE|PAGADO|TODOS');
Route::get('/enrollments/pending/{ci}', [EnrollmentListController::class, 'listasPagoPendiente']);
Route::get('/enrollments', [EnrollmentListController::class, 'index']);
Route::get('/receipts/individual/{id}', [EnrollmentListController::class, 'individual']);
Route::get('/receipts/group/{id}', [EnrollmentListController::class, 'grupal']);
Route::get('/enrrolments/participants/{id}',[EnrollmentListController::class, 'getById']);
Route::get('/person/{ci}', [PersonController::class, 'getByCi']);
Route::post('/ocr', [PaymentSlipController::class, 'process']); //probar con cuidado
Route::get('/payment/{ci}', [PaymentInquiryController::class, 'checkByCi']);