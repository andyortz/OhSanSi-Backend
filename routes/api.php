<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OlimpiadaAreaController;
use App\Http\Controllers\NivelCategoriaController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\InscripcionAreaController;
use App\Http\Controllers\TutoresControllator;
use App\Http\Controllers\OlympiadRegistrationController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\OlimpiadaGestionController;
use App\Http\Controllers\AreasFiltroController;
use App\Http\Controllers\colegiosController;
use App\Http\Controllers\OlimpistaController;
use App\Http\Controllers\VincularController;
use App\Http\Controllers\EstructuraOlimpiadaController;
use App\Http\Controllers\OlimpiadaController;
use App\Http\Controllers\VerificarInscripcionController;
use App\Http\Controllers\InscripcionNivelesController;
use App\Http\Controllers\ListaInscripcionController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\DatosExcelController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\BoletaController;
use App\Http\Controllers\TestPreprocessorController;
use App\Http\Controllers\PagoValidacionController;
use App\Http\Controllers\ConsultaPagoController;


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
    Route::get('/group/{id}', [ListaInscripcionController::class, 'groupal']);
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
