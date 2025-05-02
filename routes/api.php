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

use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\DatosExcelController;

use App\Http\Controllers\ParentescoController;
use App\Imports\OlimpistaImport;
use App\Imports\TutoresImport;
use Maatwebsite\Excel\Facades\Excel;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/olimpistas/excel', [ExcelImportController::class, 'import']);
Route::post('/registro/excel', [DatosExcelController::class, 'cleanDates']);

// Niveles
Route::post('/niveles', [NivelCategoriaController::class, 'store']);
Route::post('/areas/asociar-niveles', [NivelCategoriaController::class, 'asociarNivelesPorArea']);
Route::get('/niveles/area/{id_area}', [NivelCategoriaController::class, 'nivelesPorArea']);
Route::post('/niveles', [NivelCategoriaController::class, 'store']);
Route::post('/asociar-grados-nivel', [NivelCategoriaController::class,'asociarGrados']);

// Grados
Route::get('/grados', [GradosController::class, 'index']);
Route::get('/grados-niveles', [NivelCategoriaController::class, 'index']);

// Áreas por olimpiada
Route::get('/olimpiada/{id}/areas', [AreasController::class, 'areasPorOlimpiada']);

// Niveles por área
Route::get('/areas/{id}/niveles', [NivelCategoriaController::class, 'nivelesPorArea']);
Route::get('/get-niveles', [NivelCategoriaController::class, 'index2']);
Route::get('/olimpiadas/{id}/max-categorias', [OlimpiadaAreaController::class, 'maxCategorias']);

//Route::post('/inscripciones', [InscripcionAreaController::class, 'store']);
Route::post('/inscripciones', [InscripcionNivelesController::class, 'store']);

// inscrpcion con posible tutor
Route::post('/inscripciones-con-tutor', [InscripcionNivelesController::class, 'storeWithTutor']);

// inscripcion de varios olimpistas con un tutor
Route::post('/registrar-varios-olimpistas', [InscripcionNivelesController::class, 'registrarVarios']);

// inscripcion de varios olimpistas con varios tutores
Route::post('/inscribir-multiples-olimpistas', [InscripcionNivelesController::class, 'registrarMultiplesConTutor']);

// inscripciones por olimpista
Route::get('/olimpista/{ci}/inscripciones', [VerificarInscripcionController::class, 'getInscripcionesPorCI']);
Route::get('/olimpista/{ci}/total-inscripciones', [VerificarInscripcionController::class, 'getTotalInscripciones']);

//Asociar Tutor con Olimpista mediante tabla Parentesco
Route::post('/asociar-tutor', [ParentescoController::class, 'asociarTutor']);

// Tutores
Route::post('/tutores', [TutoresControllator::class, 'store']);
Route::get('tutores/email/{email}',[TutoresControllator::class,'getByEmail']);
Route::get('/tutores',[TutoresControllator::class,'buscarCi' ]);

Route::get('tutores/cedula/{cedula}',[TutoresControllator::class,'buscarPorCi']);


// Áreas
Route::get('/areas', [AreasController::class, 'index']);
Route::post('/areas', [AreasController::class, 'store']);
Route::get('/areas-niveles-grados', [AreasController::class, 'areasConNivelesYGrados']);

//Colegios
Route::get('/colegios', [ColegiosController::class, 'index']);
Route::get('/colegios/{id}', [ColegiosController::class, 'porProvincia']);
//Olimpiadas
Route::get('/olimpiadas', [OlimpiadaGestionController::class, 'index']);
Route::post('/olympiad-registration', [OlympiadRegistrationController::class, 'store']);
Route::get('/olympiad-registration', [OlympiadRegistrationController::class, 'index']);
Route::get('/olympiad/{gestion}', [OlimpiadaGestionController::class, 'show']);

//Olimpista Regitro
//Route::post('/student-registration', [StudentRegistrationController::class, 'store']);
//Route::get('/student-registration', [StudentRegistrationController::class, 'index']);

//Olimpista
Route::get('olimpistas/cedula/{cedula}', [OlimpistaController::class, 'getByCedula']);
Route::get('olimpistas/email/{email}', [OlimpistaController::class, 'getByEmail']);
Route::post('/olimpistas', [OlimpistaController::class, 'store']);


//Departamentos
Route::get('/departamentos', [DepartamentoController::class, 'index']);

//Provincias
Route::get('/provincias/{id}', [ProvinciaController::class, 'porDepartamento']);
Route::get('/olimpistas/{id_olimpista}/olimpiadas/{id_olimpiada}/areas-disponibles', [AreasFiltroController::class, 'obtenerAreasDisponibles']);
Route::get('/estructura-olimpiada/{id_olimpiada}', [EstructuraOlimpiadaController::class, 'obtenerEstructuraOlimpiada']);

//new db
Route::post('/vincular-olimpista-tutor', [VincularController::class, 'registrarConParentesco']);
Route::get('/olimpiada/abierta', [OlimpiadaController::class, 'verificarOlimpiadaAbierta']);
Route::get('/verificar-inscripcion', [VerificarInscripcionController::class, 'verificar']);

Route::post('/registro-olimpista', [OlimpistaController::class, 'store']);


//Areas de inscripcion para un Olimpista
Route::get('/olimpistas/{ci}/areas-niveles', [OlimpistaController::class, 'getAreasNivelesInscripcion']);

//maxima cantidad de categorias
Route::get('/olimpiada/max-categorias', [OlimpiadaController::class, 'getMaxCategorias']);

