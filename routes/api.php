<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Modules\Olympiads\Controllers\CategoryLevelController;
use App\Modules\Olympiads\Controllers\AreaController;
use App\Modules\Olympiads\Controllers\GradeController;
use App\Modules\Olympiads\Controllers\InscripcionAreaController;
use App\Modules\Persons\Controllers\TutorController;
use App\Modules\Olympiads\Controllers\OlympiadRegistrationController;
use App\Modules\Olympiads\Controllers\DepartamentController;
use App\Modules\Olympiads\Controllers\ProvinciaController;
use App\Modules\Olympiads\Controllers\OlimpiadaGestionController;
use App\Modules\Olympiads\Controllers\AreasFiltroController;
use App\Modules\Olympiads\Controllers\SchoolController;
use App\Modules\Persons\Controllers\OlympistController;
use App\Modules\Olympiads\Controllers\OlympiadController;
use App\Modules\Enrollments\Controllers\VerifyEnrollmentController;
use App\Modules\Olympiads\Controllers\InscripcionNivelesController;
use App\Modules\Enrollments\Controllers\EnrollmentListController;
use App\Modules\Enrollments\Controllers\ExcelImportController;
use App\Modules\Enrollments\Controllers\DatosExcelController;
use App\Modules\Persons\Controllers\PersonaController;
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
    Route::post('/', [PersonaController::class, 'store']); //no existe
    Route::get('/{ci}', [PersonaController::class, 'getByCi']); //si
});

Route::prefix('provinces')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [ProvinciaController::class, 'index']); //si
    Route::get('/{id}', [ProvinciaController::class, 'porDepartamento']);//si
});

Route::prefix('departaments')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [DepartamentController::class, 'index']);  //si
});

Route::prefix('enrollments')->middleware('throttle:100,1')->group(function () {
    Route::post('/with-tutor', [InscripcionNivelesController::class, 'storeWithTutor']); //si
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
    Route::get('/', [OlimpiadaGestionController::class, 'index']); //si
    Route::get('/now', [OlimpiadaGestionController::class, 'index2']); //si
    Route::get('/max-categories', [OlympiadController::class, 'getMaxCategories']); //si // falta acabar
    Route::get('/{id}/max-categories', [OlympiadController::class, 'getMaxCategoriesById']); //si
    Route::get('/{id}/levels-areas', [OlympiadController::class, 'getAreasConNiveles']); //si
    Route::get('/{id}/areas', [AreaController::class, 'areasByOlympiad']); //si
    Route::get('/{id}/management', [OlimpiadaGestionController::class, 'show']); //si 
    Route::get('/{year}/statistics', [OlympiadController::class, 'getStatistics']); //si
    Route::get('/{year}', [OlimpiadaGestionController::class, 'show']); //si
});
Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']); //si, ya ta posi
    Route::post('/registration', [DatosExcelController::class, 'cleanDates']); //si, ya ta posi
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

Route::get('/levels-areas/{id}', [CategoryLevelController::class, 'getByNivelesById']);//si
Route::post('/login', [AuthController::class, 'login']);