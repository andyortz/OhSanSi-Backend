<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Modules\Olympiads\Controllers\OlimpiadaAreaController;
use App\Modules\Olympiads\Controllers\NivelCategoriaController;
use App\Modules\Olympiads\Controllers\AreasController;
use App\Modules\Olympiads\Controllers\GradosController;
use App\Modules\Olympiads\Controllers\InscripcionAreaController;
use App\Modules\Persons\Controllers\TutoresControllator;
use App\Modules\Olympiads\Controllers\OlympiadRegistrationController;
use App\Modules\Persons\Controllers\StudentRegistrationController;
use App\Modules\Olympiads\Controllers\DepartamentoController;
use App\Modules\Olympiads\Controllers\ProvinciaController;
use App\Modules\Olympiads\Controllers\OlimpiadaGestionController;
use App\Modules\Olympiads\Controllers\AreasFiltroController;
use App\Modules\Olympiads\Controllers\colegiosController;
use App\Modules\Persons\Controllers\OlimpistaController;
use App\Modules\Persons\Controllers\VincularController;
use App\Modules\Olympiads\Controllers\EstructuraOlimpiadaController;
use App\Modules\Olympiads\Controllers\OlimpiadaController;
use App\Modules\Enrollments\Controllers\VerificarInscripcionController;
use App\Modules\Olympiads\Controllers\InscripcionNivelesController;
use App\Modules\Enrollments\Controllers\ListaInscripcionController;
use App\Modules\Enrollments\Controllers\ExcelImportController;
use App\Modules\Enrollments\Controllers\DatosExcelController;
use App\Modules\Persons\Controllers\PersonaController;
use App\Modules\Enrollments\Controllers\BoletaController;
use App\Modules\Enrollments\Controllers\TestPreprocessorController;
// use App\Http\Controllers\PagoValidacionController;
use App\Modules\Enrollments\Controllers\ConsultaPagoController;
use App\Modules\Olympiads\Controllers\AuthController;
use App\Imports\OlimpistaImport;
use App\Imports\TutoresImport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('olympists')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlimpistaController::class, 'store']);
    Route::get('/{ci}', [OlimpistaController::class, 'getByCedula']);
    Route::get('/{ci}/enrollments', [VerificarInscripcionController::class, 'getInscripcionesPorCI']);
    Route::get('/{ci}/areas-levels', [OlimpistaController::class, 'getAreasNivelesInscripcion']); 
});

Route::prefix('person')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [PersonController::class, 'store']);
    Route::get('/{ci}', [PersonaController::class, 'getByCi']);
});

Route::prefix('provinces')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [ProvinciaController::class, 'index']); 
    Route::get('/{id}', [ProvinciaController::class, 'porDepartamento']);
});

Route::prefix('departaments')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [DepartamentoController::class, 'index']);
});

Route::prefix('enrollments')->middleware('throttle:100,1')->group(function () {
    Route::post('/with-tutor', [InscripcionNivelesController::class, 'storeWithTutor']); //NO
    Route::post('/one', [InscripcionNivelesController::class, 'storeOne']);
    Route::get('/participants/{id}',[ListaInscripcionController::class, 'getById']);
    Route::get('/pending/{ci}', [ListaInscripcionController::class, 'listasPagoPendiente']);
    Route::get('/{ci}/{status}', [ListaInscripcionController::class, 'obtenerPorResponsable'])
    ->where('estado', 'PENDIENTE|PAGADO|TODOS');
});

Route::prefix('receipts')->middleware('throttle:100,1')->group(function () {
    Route::get('/individual/{id}', [ListaInscripcionController::class, 'individual']);    
    Route::get('/group/{id}', [ListaInscripcionController::class, 'grupal']);
});

Route::prefix('schools')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [ColegiosController::class, 'index']);
    Route::get('/names', [ColegiosController::class, 'soloNombres']); 
    Route::get('/provinces/{id}', [ColegiosController::class, 'porProvincia']);
});

Route::post('/ocr', [BoletaController::class, 'procesar']);
Route::get('/payment/{ci}', [ConsultaPagoController::class, 'verificarPorCi']);

Route::prefix('olympiads')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympiadRegistrationController::class, 'store']);
    Route::get('/', [OlimpiadaGestionController::class, 'index']);
    Route::get('/now', [OlimpiadaGestionController::class, 'index2']);
    Route::get('/max-categories', [OlimpiadaController::class, 'getMaxCategorias']);
    Route::get('/{id}/max-categories', [OlimpiadaAreaController::class, 'maxCategorias']);
    Route::get('/{id}/levels-areas', [OlimpiadaController::class, 'getAreasConNiveles']);
    Route::get('/{id}/areas', [AreasController::class, 'areasPorOlimpiada']);
    Route::get('/{year}/statistic', [OlimpiadaController::class, 'getStatistics']);
    Route::get('/{year}/management', [OlimpiadaGestionController::class, 'show']);
    Route::get('/{id}/statistics', [OlimpiadaController::class, 'getStatistics']);
    Route::get('/{year}', [OlimpiadaGestionController::class, 'show']);
});
Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']);
    Route::post('/registration', [DatosExcelController::class, 'cleanDates']);
});
Route::prefix('levels')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [NivelCategoriaController::class, 'index3']);
    Route::post('/', [NivelCategoriaController::class, 'newCategoria']);
    Route::get('/areas/{id}', [NivelCategoriaController::class, 'nivelesPorArea']);    
    Route::get('/{id}', [NivelCategoriaController::class, 'index4']);
});

Route::prefix('areas')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [AreasController::class, 'index']);
    Route::post('/', [AreasController::class, 'store']);
    Route::post('/association', [NivelCategoriaController::class, 'asociarNivelesPorArea']);
});

Route::prefix('grades')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [GradosController::class, 'index']);
    Route::post('/levels', [NivelCategoriaController::class,'asociarGrados']);
    Route::get('/levels/{id}', [NivelCategoriaController::class, 'getById']);
});
Route::post('/tutors', [TutoresControllator::class, 'store']);
Route::get('/tutors/{ci}',[TutoresControllator::class,'buscarPorCi']);
Route::post('/payment/verification', [PagoValidacionController::class, 'verificar']);

Route::get('/levels-areas/{id}', [NivelCategoriaController::class, 'getByNivelesById']);
Route::post('/login', [AuthController::class, 'login']);