<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Modules\Olympiads\Controllers\CategoryLevelController;
use App\Modules\Olympiads\Controllers\AreaController;
use App\Modules\Olympiads\Controllers\GradeController;
use App\Modules\Persons\Controllers\TutorController;
use App\Modules\Olympiads\Controllers\OlympiadRegistrationController;
use App\Modules\Olympiads\Controllers\DepartmentController;
use App\Modules\Olympiads\Controllers\ProvinceController;
use App\Modules\Olympiads\Controllers\OlympiadManagmentController;
use App\Modules\Olympiads\Controllers\SchoolController;
use App\Modules\Persons\Controllers\OlympistController;
use App\Modules\Olympiads\Controllers\OlympiadController;
use App\Modules\Enrollments\Controllers\VerifyEnrollmentController;
use App\Modules\Enrollments\Controllers\LevelEnrollmentController;
use App\Modules\Enrollments\Controllers\EnrollmentListController;
use App\Modules\Enrollments\Controllers\ExcelImportController;
use App\Modules\Enrollments\Controllers\ExcelDataController;
use App\Modules\Persons\Controllers\PersonController;
use App\Modules\Enrollments\Controllers\PaymentSlipController;
use App\Modules\Enrollments\Controllers\TestPreprocessorController;
use App\Modules\Enrollments\Controllers\PaymentConsultationController;
use App\Modules\Olympiads\Controllers\AuthController;
use App\Imports\OlimpistaImport;
use App\Imports\TutoresImport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('olympists')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympistController::class, 'store']); //si, ta posi
    Route::get('/{ci}', [OlympistController::class, 'getByCi']);//si, ta posi
    Route::get('/{ci}/enrollments', [VerifyEnrollmentController::class, 'getEnrollmentsByCI']);//si, falta mas datos para probar
    Route::get('/{ci}/areas-levels', [OlympistController::class, 'getEnrollmentAreaLevels']); //si, ta posi
});

Route::prefix('person')->middleware('throttle:100,1')->group(function () { 
    // Route::post('/', [PersonController::class, 'store']); //no existe
    Route::get('/{ci}', [PersonController::class, 'getByCi']); //si
});

Route::prefix('provinces')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [ProvinceController::class, 'index']); //si
    Route::get('/{id}', [ProvinceController::class, 'byDepartment']);//si
});

Route::prefix('departments')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);  //si
});

Route::prefix('enrollments')->middleware('throttle:100,1')->group(function () {
    Route::post('/with-tutor', [LevelEnrollmentController::class, 'storeWithTutor']); //si, ya ta posi
    // Route::post('/one', [InscripcionNivelesController::class, 'storeOne']);  //no se usa creo
    Route::get('/participants/{id}',[EnrollmentListController::class, 'getById']); //si, ta posi
    Route::get('/pending/{ci}', [EnrollmentListController::class, 'pendingPaymentlists']); //si, falta datos para probar
    Route::get('/{ci}/{status}', [EnrollmentListController::class, 'getByResponsible'])//si, ta posi, falta datos para probar bien
    ->where('estado', 'PENDIENTE|PAGADO|TODOS');
});

Route::prefix('receipts')->middleware('throttle:100,1')->group(function () {
    Route::get('/individual/{id}', [EnrollmentListController::class, 'individual']);   //si, falta datos
    Route::get('/group/{id}', [EnrollmentListController::class, 'group']);   //si, falta datos
});

Route::prefix('schools')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [SchoolController::class, 'index']); //si
    Route::get('/names', [SchoolController::class, 'onlyNames']); //si
    Route::get('/provinces/{id}', [SchoolController::class, 'byProvince']);//si
});

Route::post('/ocr', [PaymentSlipController::class, 'process']); //si, falta datos
Route::get('/payment/{ci}', [PaymentConsultationController::class, 'verificarPorCi']); //si, falta datos

Route::prefix('olympiads')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympiadRegistrationController::class, 'store']); //si
    Route::get('/', [OlympiadManagmentController::class, 'index']); //si
    Route::get('/now', [OlympiadManagmentController::class, 'now']); //si
    Route::get('/max-categories', [OlympiadController::class, 'getMaxCategories']); //si // falta acabar
    Route::get('/{id}/max-categories', [OlympiadController::class, 'getMaxCategoriesById']); //si
    Route::get('/{id}/levels-areas', [OlympiadController::class, 'getAreasWithLevels']); //si
    Route::get('/{id}/areas', [AreaController::class, 'areasByOlympiad']); //si
    Route::get('/{year}/management', [OlympiadManagmentController::class, 'show']); //si 
    //Route::get('/{year}', [OlympiadManagmentController::class, 'show']); //si lo mismo?
    Route::get('/{year}/statistics', [OlympiadController::class, 'getStatistics']); //si
});
Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']); //si, ya ta posi
    Route::post('/registration', [ExcelDataController::class, 'cleanDates']); //si, ya ta posi
});
Route::prefix('levels')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [CategoryLevelController::class, 'index']); //si
    Route::post('/', [CategoryLevelController::class, 'store']); //si
    Route::get('/{id}', [CategoryLevelController::class, 'getByOlympiad']); //si
});

Route::prefix('areas')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [AreaController::class, 'index']);//si
    Route::post('/', [AreaController::class, 'store']);//si
    Route::post('/association', [CategoryLevelController::class, 'associateLevelsWithArea']); //ojito lo cambie
    // Masomenos, como que masomenos mamahuebo???
}); 

Route::prefix('grades')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [GradeController::class, 'index']);//si
    Route::post('/levels', [CategoryLevelController::class,'associateGrades']);//si
    Route::get('/levels/{id}', [CategoryLevelController::class, 'getById']);//si
});
Route::post('/tutors', [TutorController::class, 'store']); //si, ta posi
Route::get('/tutors/{ci}',[TutorController::class,'buscarPorCi']); //si, ta posi
Route::post('/payment/verification', [PagoValidacionController::class, 'verificar']);

Route::get('/levels-areas/{id}', [CategoryLevelController::class, 'getLevelsById']);//si
Route::post('/login', [AuthController::class, 'login']);