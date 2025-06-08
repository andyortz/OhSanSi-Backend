<?php

use App\Modules\Olympists\Controllers\OlympistController;
use App\Modules\Olympists\Controllers\PersonController;
use App\Modules\Olympists\Controllers\DepartamentController;
use App\Modules\Olympists\Controllers\EnrollmentController;
use App\Modules\Olympists\Controllers\ExcelImportController;
use App\Modules\Olympists\Controllers\ExcelDataController;


Route::prefix('olympists')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympistController::class, 'store']);
    Route::get('/{ci}', [OlympistController::class, 'getByCedula']);

    //Revisar estos falta algunas traducciones
    Route::get('/{ci}/enrollments', [EnrollmentController::class, 'getEnrollmentsByCi']);
    
    Route::get('/{ci}/areas-levels', [OlympistController::class, 'areasLevels']);

    Route::post('/{id}/upload-payment', [OlympistController::class, 'uploadPayment']);
});

Route::prefix('person')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [PersonController::class, 'store']);
    Route::get('/{ci}', [PersonController::class, 'show']);
});

Route::prefix('province')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [ProvinceController::class, 'index']); 
    Route::get('/{id}', [ProvinciaController::class, 'byDepartment']);
});

Route::prefix('departament')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [DepartamentController::class, 'index']);
});

Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']);
    Route::post('/registration', [ExcelDataController::class, 'cleanDates']);
});

Route::prefix('enrrolments')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [EnrollmentListController::class, 'index']);
    Route::get('/{ci}/{estado}', [EnrollmentListController::class, 'getByResponsible'])
        ->where('status', 'PENDIENTE|PAGADO|TODOS');
    Route::post('/with-tutor', [LevelEnrollmentController::class, 'storeWithTutor']);
    
    //Revisar estos falta algunas traducciones
    Route::get('/participants/{id}',[EnrollmentListController::class, 'getById']);

    Route::get('/pending/{ci}', [EnrollmentListController::class, 'listasPagoPendiente']);
});

//Revise hasta aqui, falta lo de abajo
Route::prefix('receipts')->middleware('throttle:100,1')->group(function () {
    Route::get('/individual/{id}', [EnrollmentListController::class, 'individual']);
    Route::get('/group/{id}', [EnrollmentListController::class, 'grupal']);
    
});
Route::prefix('tutors')->middleware('throttle:100,1')->group(function () {
    Route::post('/tutors', [TutorsController::class, 'store']);
    Route::get('/tutors/id/{ci}',[TutorsController::class,'searchByCi']);
});

Route::prefix('schools')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [SchoolController::class, 'index']);
    Route::get('/names', [SchoolController::class, 'justNames']); 
    Route::get('/provinces/{id}', [SchoolController::class, 'byProvince']);
});

Route::post('/ocr', [PaymentSlipController::class, 'process']);
Route::get('/payment/{ci}', [PaymentInquiryController::class, 'checkByCi']);
