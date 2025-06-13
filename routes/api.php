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
    Route::post('/', [OlimpistaController::class, 'store']); //si
    Route::get('/{ci}', [OlimpistaController::class, 'getByCedula']);//si
    Route::get('/{ci}/enrollments', [VerificarInscripcionController::class, 'getInscripcionesPorCI']);//si
    Route::get('/{ci}/areas-levels', [OlimpistaController::class, 'getAreasNivelesInscripcion']); //si
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
    Route::get('/', [DepartamentoController::class, 'index']);  //si
});

Route::prefix('enrollments')->middleware('throttle:100,1')->group(function () {
    Route::post('/with-tutor', [InscripcionNivelesController::class, 'storeWithTutor']); //si
    // Route::post('/one', [InscripcionNivelesController::class, 'storeOne']);  //no se usa
    Route::get('/participants/{id}',[ListaInscripcionController::class, 'getById']); //si
    Route::get('/pending/{ci}', [ListaInscripcionController::class, 'listasPagoPendiente']); //mmm
    Route::get('/{ci}/{status}', [ListaInscripcionController::class, 'obtenerPorResponsable'])//si
    ->where('estado', 'PENDIENTE|PAGADO|TODOS');
});

Route::prefix('receipts')->middleware('throttle:100,1')->group(function () {
    Route::get('/individual/{id}', [ListaInscripcionController::class, 'individual']);   //si 
    Route::get('/group/{id}', [ListaInscripcionController::class, 'grupal']);   //si
});

Route::prefix('schools')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [colegiosController::class, 'index']); //si
    Route::get('/names', [colegiosController::class, 'soloNombres']); //si
    Route::get('/provinces/{id}', [colegiosController::class, 'porProvincia']);//si
});

Route::post('/ocr', [BoletaController::class, 'procesar']); //si
Route::get('/payment/{ci}', [ConsultaPagoController::class, 'verificarPorCi']); //si

Route::prefix('olympiads')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympiadRegistrationController::class, 'store']); //si
    Route::get('/', [OlimpiadaGestionController::class, 'index']); //si
    Route::get('/now', [OlimpiadaGestionController::class, 'index2']); //si
    Route::get('/max-categories', [OlimpiadaController::class, 'getMaxCategorias']); //si
    Route::get('/{id}/max-categories', [OlimpiadaAreaController::class, 'maxCategorias']); //si
    Route::get('/{id}/levels-areas', [OlimpiadaController::class, 'getAreasConNiveles']); //si
    Route::get('/{id}/areas', [AreasController::class, 'areasPorOlimpiada']); //si
    Route::get('/{id}/management', [OlimpiadaGestionController::class, 'show']); //si cambié id -> year
    Route::get('/{year}/statistics', [OlimpiadaController::class, 'getStatistics']); //si
    Route::get('/{year}', [OlimpiadaGestionController::class, 'show']); //si
});
Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']); //si
    Route::post('/registration', [DatosExcelController::class, 'cleanDates']); //si
});
Route::prefix('levels')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [NivelCategoriaController::class, 'index3']); //si
    Route::post('/', [NivelCategoriaController::class, 'newCategoria']); //si
    Route::get('/areas/{id}', [NivelCategoriaController::class, 'nivelesPorArea']);  //no da, no sale resultados
    Route::get('/{id}', [NivelCategoriaController::class, 'index4']); //si
});

Route::prefix('areas')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [AreasController::class, 'index']);//si
    Route::post('/', [AreasController::class, 'store']);//si
    Route::post('/association', [NivelCategoriaController::class, 'asociarNivelesPorArea']); //Masomenos
}); 

Route::prefix('grades')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [GradosController::class, 'index']);//si
    Route::post('/levels', [NivelCategoriaController::class,'asociarGrados']);//si
    Route::get('/levels/{id}', [NivelCategoriaController::class, 'getById']);//si
});
Route::post('/tutors', [TutoresControllator::class, 'store']); //si
Route::get('/tutors/{ci}',[TutoresControllator::class,'buscarPorCi']); //si
Route::post('/payment/verification', [PagoValidacionController::class, 'verificar']);

Route::get('/levels-areas/{id}', [NivelCategoriaController::class, 'getByNivelesById']);//si
Route::post('/login', [AuthController::class, 'login']);