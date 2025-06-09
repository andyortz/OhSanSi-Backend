<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Olympist\Controllers\OlympistController;
use App\Modules\Olympist\Controllers\PersonController;
use App\Modules\Olympist\Controllers\DepartamentController;
use App\Modules\Olympist\Controllers\ProvinceController;
use App\Modules\Olympiad\Controllers\EnrollmentController;
use App\Modules\Olympiad\Controllers\EnrollmentListController;
use App\Modules\Olympist\Controllers\ExcelImportController;
use App\Modules\Olympist\Controllers\ExcelDataController;
use App\Modules\Olympist\Controllers\SchoolController;
use App\Modules\Olympist\Controllers\PaymentSlipController;
use App\Modules\Olympist\Controllers\PaymentInquiryController;

Route::prefix('olympists')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympistController::class, 'store']);
    Route::get('/{ci}', [OlympistController::class, 'getByCedula']);
    Route::get('/{ci}/enrollments', [EnrollmentController::class, 'getEnrollmentsByCi']);
    Route::get('/{ci}/areas-levels', [OlympistController::class, 'areasLevels']); //ojito con este
    
    Route::post('/{id}/upload-payment', [OlympistController::class, 'uploadPayment']); //???
});

Route::prefix('person')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [PersonController::class, 'store']);
    Route::get('/{ci}', [PersonController::class, 'show']);
});

Route::prefix('province')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [ProvinceController::class, 'index']); 
    Route::get('/{id}', [ProvinceController::class, 'byDepartment']);
});

Route::prefix('departament')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [DepartamentController::class, 'index']);
});

Route::prefix('enrollments')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [EnrollmentListController::class, 'index']); // NO
    Route::get('/{ci}/{status}', [EnrollmentListController::class, 'getByResponsible'])
        ->where('status', 'PENDIENTE|PAGADO|TODOS');
    Route::post('/with-tutor', [LevelEnrollmentController::class, 'storeWithTutor']); //NO
    Route::get('/participants/{id}',[EnrollmentListController::class, 'getById']);
    Route::get('/pending/{ci}', [EnrollmentListController::class, 'pendingPaymentLists']);
});

Route::prefix('receipts')->middleware('throttle:100,1')->group(function () {
    Route::get('/individual/{id}', [EnrollmentListController::class, 'individual']);    
    Route::get('/group/{id}', [EnrollmentListController::class, 'group']);
});

Route::prefix('schools')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [SchoolController::class, 'index']);
    Route::get('/names', [SchoolController::class, 'onlyNames']); 
    Route::get('/provinces/{id}', [SchoolController::class, 'byProvince']);
});

Route::post('/ocr', [PaymentSlipController::class, 'process']);
Route::get('/payment/{ci}', [PaymentInquiryController::class, 'checkByCi']);
